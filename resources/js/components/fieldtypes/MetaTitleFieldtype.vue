<template>
    <div class="meta-field-validator__outer">
        <div class="meta-field-validator__field-container">
            <div class="input-group">
                <input :value="generateTitle(value)" @input="update($event.target.value)" @keyup="handleKeyUp" type="text" :name="name" :id="id" :placeholder="generatePlaceholder()" class="input-text" />
            </div>
            <progress max="70" :value="contentLength" :class="'meta-field-validator__progress meta-field-validator__progress--' + validation.step" />
        </div>
        <span class="meta-field-validator__caption" v-html="validation.caption"></span>
    </div>
</template>

<script>
    import MetaDataAnalyser from './mixins/MetaDataAnalyser';

    export default {
        mixins: [Fieldtype, MetaDataAnalyser],

        inject: ['storeName'],

        methods: {
            generatePlaceholder() {
                const state = this.$store.state.publish[this.storeName];
                return this.meta.site_name
                    ? `${state.values.title || ''} ${this.meta.title_separator} ${this.meta.site_name}`
                    : state.values.title || '';
            },
            /**
             * Generates the title based on localisation fields.
             * If the current site is not the default locale and the 'meta_title' is not localised,
             * it returns an empty string; otherwise, it returns the provided value.
             *
             * @param {string} value - The original title value to potentially return.
             * @return {string} - The localised title or an empty string.
             */
            generateTitle(value) {
                // Access the publish state from the Vuex store
                const state = this.$store.state.publish[this.storeName];

                // Check if the state exists, has localizedFields, and if the current site is not the default locale
                if (state && state.localizedFields && this.meta.default_locale !== state.site) {
                    // Return the value only if 'meta_title' is a localised field
                    return state.localizedFields.includes('meta_title') ? value : '';
                }

                // Return the provided value as default
                return value;
            },
            validateMeta(length) {
                let validation;
                switch (true) {
                    case length === 0:
                        validation = {
                            step: "valid",
                            caption:
                            "You have not set a meta title, the value for the page title will be used."
                        };
                        break;
                    case length < 20:
                        validation = {
                            step: "warn",
                            caption: "Your meta title could be longer."
                        };
                        break;
                    case length >= 20 && length <= 70:
                        validation = { step: "valid", caption: "Your meta title is a good length." };
                    break;
                    case length > 70:
                        validation = {
                            step: "err",
                            caption:
                            "Your meta title is too long, <strong>the ideal length is between 20 and 70 characters.</strong>"
                        };
                        break;
                }
                return validation;
            }
        }
    }
</script>
