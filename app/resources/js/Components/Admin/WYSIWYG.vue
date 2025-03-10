<template>
    <ckeditor :editor="editor" v-model="model" :config="editorConfig" class="article-content"></ckeditor>
</template>


<script>
import {Ckeditor} from '@ckeditor/ckeditor5-vue';
import {CKEditorCustomImageUploader} from '@/Classes/CKEditorCustomImageUploader.js'
import {
    ClassicEditor,
    Alignment,
    Autoformat,
    AutoImage,
    AutoLink,
    Autosave,
    BlockQuote,
    Bold,
    Bookmark,
    CodeBlock,
    Essentials,
    FontBackgroundColor,
    FontColor,
    FontFamily,
    FontSize,
    GeneralHtmlSupport,
    Heading,
    Highlight,
    HorizontalLine,
    HtmlEmbed,
    ImageBlock,
    ImageCaption,
    ImageInline,
    ImageInsert,
    ImageInsertViaUrl,
    ImageResize,
    ImageStyle,
    ImageTextAlternative,
    ImageToolbar,
    ImageUpload,
    Indent,
    IndentBlock,
    Italic,
    Link,
    LinkImage,
    List,
    ListProperties,
    MediaEmbed,
    Paragraph,
    PasteFromOffice,
    RemoveFormat,
    SimpleUploadAdapter,
    SourceEditing,
    Strikethrough,
    Style,
    Subscript,
    Superscript,
    Table,
    TableCaption,
    TableCellProperties,
    TableColumnResize,
    TableProperties,
    TableToolbar,
    TextTransformation,
    TodoList,
    Underline,
} from 'ckeditor5';
import 'ckeditor5/ckeditor5.css';

export default {
    props: {
        modelValue: String | null,
        entity: String
    },
    components: {
        ckeditor: Ckeditor
    },
    data() {
        const self = this;

        function MyCustomUploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
                // Configure the URL to the upload script in your back-end here!
                return new CKEditorCustomImageUploader(loader, self.entity);
            };
        }

        return {
            editor: ClassicEditor,
            model: this.modelValue || '',
            editorConfig: {
                extraPlugins: [MyCustomUploadAdapterPlugin],
                plugins: [
                    Alignment,
                    Autoformat,
                    AutoImage,
                    AutoLink,
                    Autosave,
                    BlockQuote,
                    Bold,
                    Bookmark,
                    CodeBlock,
                    Essentials,
                    FontBackgroundColor,
                    FontColor,
                    FontFamily,
                    FontSize,
                    GeneralHtmlSupport,
                    Heading,
                    Highlight,
                    HorizontalLine,
                    HtmlEmbed,
                    ImageBlock,
                    ImageCaption,
                    ImageInline,
                    ImageInsert,
                    ImageInsertViaUrl,
                    ImageResize,
                    ImageStyle,
                    ImageTextAlternative,
                    ImageToolbar,
                    ImageUpload,
                    Indent,
                    IndentBlock,
                    Italic,
                    Link,
                    LinkImage,
                    List,
                    ListProperties,
                    MediaEmbed,
                    Paragraph,
                    PasteFromOffice,
                    RemoveFormat,
                    SimpleUploadAdapter,
                    SourceEditing,
                    Strikethrough,
                    Style,
                    Subscript,
                    Superscript,
                    Table,
                    TableCaption,
                    TableCellProperties,
                    TableColumnResize,
                    TableProperties,
                    TableToolbar,
                    TextTransformation,
                    TodoList,
                    Underline
                ],
                link: {
                    addTargetToExternalLinks: true, // Automatically adds target="_blank" to external links
                    decorators: {
                        openInNewTab: {
                            mode: 'manual',
                            label: 'Open in a new tab',
                            attributes: {
                                target: '_blank',
                                rel: 'noopener noreferrer'
                            }
                        },
                        bigButton: {
                            mode: 'manual',
                            label: 'Big Button',
                            attributes: {
                                class: 'btn-link btn-big'
                            },
                            class: 'btn-link btn-big'
                        },
                        smButton: {
                            mode: 'manual',
                            label: 'Small Button',
                            attributes: {
                                class: 'btn-link btn-sm'
                            },
                            class: 'btn-link btn-sm'
                        }
                    }
                },
                toolbar: {
                    items: [
                        'sourceEditing',
                        '|',
                        'heading',
                        'style',
                        '|',
                        'fontSize',
                        'fontFamily',
                        'fontColor',
                        'fontBackgroundColor',
                        '|',
                        'bold',
                        'italic',
                        'underline',
                        'strikethrough',
                        'subscript',
                        'superscript',
                        'removeFormat',
                        '|',
                        'horizontalLine',
                        'link',
                        'bookmark',
                        'insertImage',
                        'mediaEmbed',
                        'insertTable',
                        'highlight',
                        'blockQuote',
                        'codeBlock',
                        'htmlEmbed',
                        '|',
                        'alignment',
                        '|',
                        'bulletedList',
                        'numberedList',
                        'todoList',
                        'outdent',
                        'indent'
                    ],
                    shouldNotGroupWhenFull: true
                },
                htmlSupport: {
                    allow: [
                        {
                            name: /^.*$/,
                            styles: true,
                            attributes: true,
                            classes: true
                        }
                    ]
                },
                image: {
                    toolbar: [
                        'toggleImageCaption',
                        'imageTextAlternative',
                        '|',
                        'imageStyle:inline',
                        'imageStyle:wrapText',
                        'imageStyle:breakText',
                        '|',
                        'resizeImage'
                    ]
                },
                licenseKey: 'GPL'
            }

            // ...
        };
    },
    watch: {
        model: {
            handler(val) {
                this.$emit('update:modelValue', val);
            }
        },
        modelValue: {
            handler(val) {
                this.model = this.modelValue;
            }
        }
    }
}
</script>


<style lang="scss">

.dark {
    /* Overrides the border radius setting in the theme. */
    --ck-border-radius: 4px;

    /* Overrides the default font size in the theme. */
    --ck-font-size-base: 14px;

    /* Helper variables to avoid duplication in the colors. */
    --ck-custom-background: hsl(205, 28%, 17%) !important;
    --ck-custom-foreground: hsl(255, 3%, 18%) !important;
    --ck-custom-border: hsl(300, 1%, 22%) !important;
    --ck-custom-white: hsl(0, 0%, 100%) !important;
    --ck-color-base-background: rgb(31, 45, 55) !important;

    /* -- Overrides generic colors. ------------------------------------------------------------- */

    --ck-color-base-foreground: var(--ck-custom-background) !important;
    --ck-color-focus-border: hsl(208, 90%, 62%) !important;
    --ck-color-text: hsl(0, 0%, 98%) !important;
    --ck-color-shadow-drop: hsla(0, 0%, 0%, 0.2) !important;
    --ck-color-shadow-inner: hsla(0, 0%, 0%, 0.1) !important;

    /* -- Overrides the default .ck-button class colors. ---------------------------------------- */

    --ck-color-button-default-background: var(--ck-custom-background) !important;
    --ck-color-button-default-hover-background: hsl(270, 1%, 22%) !important;
    --ck-color-button-default-active-background: hsl(270, 2%, 20%) !important;
    --ck-color-button-default-active-shadow: hsl(270, 2%, 23%) !important;
    --ck-color-button-default-disabled-background: var(--ck-custom-background) !important;

    --ck-color-button-on-background: var(--ck-custom-foreground) !important;
    --ck-color-button-on-hover-background: hsl(255, 4%, 16%) !important;
    --ck-color-button-on-active-background: hsl(255, 4%, 14%) !important;
    --ck-color-button-on-active-shadow: hsl(240, 3%, 19%) !important;
    --ck-color-button-on-disabled-background: var(--ck-custom-foreground) !important;

    --ck-color-button-action-background: hsl(168, 76%, 42%) !important;
    --ck-color-button-action-hover-background: hsl(168, 76%, 38%) !important;
    --ck-color-button-action-active-background: hsl(168, 76%, 36%) !important;
    --ck-color-button-action-active-shadow: hsl(168, 75%, 34%) !important;
    --ck-color-button-action-disabled-background: hsl(168, 76%, 42%) !important;
    --ck-color-button-action-text: var(--ck-custom-white) !important;

    --ck-color-button-save: hsl(120, 100%, 46%) !important;
    --ck-color-button-cancel: hsl(15, 100%, 56%) !important;

    /* -- Overrides the default .ck-dropdown class colors. -------------------------------------- */

    --ck-color-dropdown-panel-background: var(--ck-custom-background) !important;
    --ck-color-dropdown-panel-border: var(--ck-custom-foreground) !important;

    /* -- Overrides the default .ck-dialog class colors. ----------------------------------- */

    --ck-color-dialog-background: var(--ck-custom-background) !important;
    --ck-color-dialog-form-header-border: var(--ck-custom-border) !important;

    /* -- Overrides the default .ck-splitbutton class colors. ----------------------------------- */

    --ck-color-split-button-hover-background: var(--ck-color-button-default-hover-background) !important;
    --ck-color-split-button-hover-border: var(--ck-custom-foreground) !important;

    /* -- Overrides the default .ck-input class colors. ----------------------------------------- */

    --ck-color-input-background: var(--ck-custom-background) !important;
    --ck-color-input-border: hsl(257, 3%, 43%) !important;
    --ck-color-input-text: hsl(0, 0%, 98%) !important;
    --ck-color-input-disabled-background: hsl(255, 4%, 21%) !important;
    --ck-color-input-disabled-border: hsl(250, 3%, 38%) !important;
    --ck-color-input-disabled-text: hsl(0, 0%, 78%) !important;

    /* -- Overrides the default .ck-labeled-field-view class colors. ---------------------------- */

    --ck-color-labeled-field-label-background: var(--ck-custom-background) !important;

    /* -- Overrides the default .ck-list class colors. ------------------------------------------ */

    --ck-color-list-background: var(--ck-custom-background) !important;
    --ck-color-list-button-hover-background: var(--ck-color-base-foreground) !important;
    --ck-color-list-button-on-background: var(--ck-color-base-active) !important;
    --ck-color-list-button-on-background-focus: var(--ck-color-base-active-focus) !important;
    --ck-color-list-button-on-text: var(--ck-color-base-background) !important;

    /* -- Overrides the default .ck-balloon-panel class colors. --------------------------------- */

    --ck-color-panel-background: var(--ck-custom-background) !important;
    --ck-color-panel-border: var(--ck-custom-border) !important;

    /* -- Overrides the default .ck-toolbar class colors. --------------------------------------- */

    --ck-color-toolbar-background: var(--ck-custom-background) !important;
    --ck-color-toolbar-border: var(--ck-custom-border) !important;

    /* -- Overrides the default .ck-tooltip class colors. --------------------------------------- */

    --ck-color-tooltip-background: hsl(252, 7%, 14%) !important;
    --ck-color-tooltip-text: hsl(0, 0%, 93%) !important;

    /* -- Overrides the default colors used by the ckeditor5-image package. --------------------- */

    --ck-color-image-caption-background: hsl(0, 0%, 97%) !important;
    --ck-color-image-caption-text: hsl(0, 0%, 20%) !important;

    /* -- Overrides the default colors used by the ckeditor5-widget package. -------------------- */

    --ck-color-widget-blurred-border: hsl(0, 0%, 87%) !important;
    --ck-color-widget-hover-border: hsl(43, 100%, 68%) !important;
    --ck-color-widget-editable-focus-background: var(--ck-custom-white) !important;

    /* -- Overrides the default colors used by the ckeditor5-link package. ---------------------- */

    --ck-color-link-default: hsl(190, 100%, 75%) !important;
    --ck-color-base-border: rgb(55, 65, 81) !important;

    .ck-editor__editable_inline {
        @apply text-gray-300;
    }
}
</style>
