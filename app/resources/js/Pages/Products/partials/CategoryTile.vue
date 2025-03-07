<template>
    <!-- Product Card -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
        <img class="w-full object-cover" :src="category.thumbnail" :alt="category.title">
        <div class="p-4">
            <h3 class="text-lg dark:text-gray-200 font-bold">{{ category.title }}</h3>
            <p class="mt-4 text-gray-600 dark:text-gray-200 line-clamp-2 min-h-12">{{category.headline}}</p>
            <div class="mt-4 flex space-x-2">
                <a :href="route('product_categories.show', category.slug)"
                    class="text-center px-4 inline-block text-white rounded-lg w-full text-xl py-3 mb-2 bg-primary-500
                    font-bold mt-4 hover:bg-primary-400">
                    Explore
                </a>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "CategoryTile",
    props: {
        category: Object
    }
}
</script>
