<?php

namespace App\Http\Resources;

use App\Helpers\HtmlHelper;
use App\Http\Requests\Admin\ArticleRequest;
use App\Http\Resources\Admin\AdminShort;
use App\Models\Article;
use App\Models\ArticleVote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ArticlePublic extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = Auth::user();

        $favorites = !empty($user) ? $user->getFavoriteArticlesList() : [];

        /**
         * @var $this Article
         */
        /**
         * @var $articleRequest ArticleRequest
         */
        $articleRequest = $this->request;
        $articleRequestAuthor = !empty($articleRequest) && !empty($articleRequest->user)  ? $articleRequest->user : null;
        if (!empty($articleRequestAuthor && !$articleRequestAuthor->prompt_attribution)) {
            $articleRequestAuthor = new User([
                'first_name' => 'Anonymous',
                'last_name' => '',
                'slug' => null
            ]);
        }
        $defaultImage = $this->is_page ? null : url('/images/article_placeholder.png');
        $headline = $this->headline;
        $title = $this->title;
        $link = route('articles.show', $this->slug);
        $image = $this->getFirstMediaUrl('image', 'webp') ?: $defaultImage;
        $imageSrcSet = $this->getFirstMedia('image')?->getSrcset('webp') ?? null;
        $thumbnail = $this->getFirstMediaUrl('image', 'thumbnail') ?: $defaultImage;

        // Referenced Entity data
        if(!empty($this->entity)) {
            $title = $this->entity->title;
            $headline = $this->entity->headline;
            $image = $this->entity->getFirstMediaUrl('image', 'webp') ?: $defaultImage;
            $imageSrcSet = $this->entity->getFirstMedia('image')?->getSrcset('webp') ?? null;
            $thumbnail = $this->entity->getFirstMediaUrl('image', 'thumbnail') ?: $defaultImage;
            $link = $this->entity->getLink();
        }

        try {
            $query = ArticleVote::query()->where('article_id', $this->id);
            if (!empty($user)) {
                $query->where(function ($q) use ($user, $request) {
                    $q->where('user_id', $user->id);
                    $q->orWhereIn('client_id', [$user->uuid, $request->get('fingerprint')]);
                });
            } else {
                $query->where('client_id', $request->get('fingerprint'));
            }
            $record = $query->first();
            $myVote = $record?->vote ?? null;
        } catch (\Throwable $exception) {
            \Log::error('[Article Public myVote] ' . $exception->getMessage());
            $myVote = null;
        }

        return [
            'id' => $this->uuid,
            'title' => $title,
            'slug' => $this->slug,
            'link' => $link,
            'content' => HtmlHelper::insertInTheMiddle($this->content, "<middle/>"),
            'headline' => $headline,
            'author' => !empty($this->author) ? new AdminShort($this->author) : null,
            'tags' => $this->tags->transform(function ($tag) {
                return $tag->name;
            }),
            'published_at' => $this->published_at,
            'image' => $image,
            'imageSrcSet' => $imageSrcSet,
            'image_caption' => $this->image_caption,
            'thumbnail' => $thumbnail,
            'is_page' => $this->is_page,
            'promptAuthor' => !empty($articleRequestAuthor) ? new PromptAuthor($articleRequestAuthor) : null,
            'is_image_being_generated' => $this->is_image_being_generated,
            'seo_title' => $this->seo_title,
            'seo_headline' => $this->seo_headline,
            'is_favorite' => in_array($this->slug, $favorites),
            'vote_stat' => $this->vote_stat,
            'vote_cnt' => $this->vote_cnt,
            'vote_avg' => $this->vote_avg,
            'vote_sum' => $this->vote_sum,
            'vote_cnf' => [
                'max' => ArticleVote::MAX_VOTE,
                'min' => ArticleVote::MIN_VOTE
            ],
            'my_vote' => $myVote
        ];
    }
}
