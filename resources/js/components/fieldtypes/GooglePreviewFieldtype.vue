<template>
    <div class="google-styles google-preview__preview z-depth-1">
        <div>
            <span class="google-styles__title">{{ this.previewParts.title }}</span>
            <span class="google-styles__link">{{ this.previewParts.url }}</span>
            <p class="google-styles__description">{{ this.previewParts.description }}</p>
        </div>
    </div>
</template>

<script>
    export default {
        mixins: [Fieldtype],

        inject: ['storeName'],

        computed: {
            previewParts() {
                const state = this.$store.state.publish[this.storeName];
                const {
                    meta_title,
                    meta_description,
                    slug,
                    title,
                } = state.values;
                const {
                    site_name,
                    site_url,
                    title_separator,
                } = this.meta;

                return {
                    title: meta_title || `${title} ${title_separator} ${site_name}`,
                    url: `${site_url}/${slug}`,
                    description: meta_description
                }
            }
        }
    }
</script>
