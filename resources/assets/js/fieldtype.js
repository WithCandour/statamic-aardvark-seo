/**
 * Mixin for meta validation fields
 */
const MetaAnalysis = {
  data: function() {
    const contentLength = typeof this.data === 'string' ? this.data.length : 0;
    return { contentLength, validation: this._getValidation(contentLength) };
  },

  methods: {
    handleKeyUp: function(e) {
      this.contentLength = this.data.length;
      this.validation = this._getValidation(this.data.length);
    }
  }
};


/**
 * Bus for data storage
 */
const metaBus = new Vue({
  data: {
    title: '',
    description: '',
    loading: true
  }
});


/**
 * Meta title fieldtype component
 */
Vue.component("seo_box-valid_meta_title-fieldtype", {
  mixins: [Fieldtype, MetaAnalysis],

  props: ["data", "config", "name"],

  data: function() {
    return {
      title_separator: "",
      site_name: ""
    };
  },

  created: function() {
    this.$http.get(cp_url("addons/seo-box/seo-box-json")).success(response => {
      this.title_separator = response.title_separator;
      this.site_name = response.site_name;
      metaBus.title = this.data || this._generateDefaultTitle();
      metaBus.loading = false;
    });
  },

  template: `
    <div class="meta_text_validator--outer">
      <div class="meta_text_validator--field-container">
        <input v-on:keyup="handleKeyUp" :type="mode" class="form-control" v-model="data" tabindex="0" :autofocus="autofocus" :placeholder="_generateDefaultTitle()" />
        <progress max="70" :value="contentLength" :class="'meta_text_validator--progress meta_text_validator--progress--' + validation.step"></progress>
      </div>
      <span class="meta_text_validator--caption">{{ validation.caption }}</span>
    </div>
  `,

  methods: {
    _getValidation: function(length) {
      metaBus.title = this.data || this._generateDefaultTitle();
      let validation;
      switch (true) {
        case length === 0:
          validation = {
            step: "valid",
            caption:
              "You have not set a meta title, the value for the page title will be used"
          };
          break;
        case length < 20:
          validation = {
            step: "warn",
            caption: "Your meta title could be longer"
          };
          break;
        case length >= 20 && length <= 70:
          validation = { step: "valid", caption: "" };
          break;
        case length > 70:
          validation = {
            step: "err",
            caption:
              "Your meta title is too long <strong>the ideal length is between 20 and 70 characters</strong>"
          };
          break;
      }
      return validation;
    },
    _generateDefaultTitle() {
      const parentTitle = this.$parent.$parent.data.title;
      return `${parentTitle} ${this.title_separator} ${this.site_name}`;
    }
  }
});


/**
 * Meta description fieldtype component
 */
Vue.component("seo_box-valid_meta_description-fieldtype", {
  mixins: [Fieldtype, MetaAnalysis],

  props: ["data", "config", "name"],

  data: function() {
    return {
      placeholder:
        "No meta description has been set for this page, search engines will use a relevent body of text from the page instead."
    };
  },

  template: `
    <div class="meta_text_validator--outer">
      <div class="meta_text_validator--field-container">
        <textarea v-on:keyup="handleKeyUp" class="form-control" v-model="data" v-el:textarea v-elastic :placeholder="placeholder"></textarea>
        <progress max="300" :value="contentLength" :class="'meta_text_validator--progress meta_text_validator--progress--' + validation.step"></progress>
      </div>
      <span class="meta_text_validator--caption">{{ validation.caption}} </span>
    </div>
  `,

  methods: {
    _getValidation: function(length) {
      metaBus.description = this.data || this.placeholder;
      let validation;
      switch (true) {
        case length === 0:
          validation = {
            step: "valid",
            caption: "You have not set a meta description for this page"
          };
          break;
        case length < 50:
          validation = {
            step: "warn",
            caption: "Your meta description could be longer"
          };
          break;
        case length >= 20 && length <= 300:
          validation = { step: "valid", caption: "" };
          break;
        case length > 300:
          validation = {
            step: "err",
            caption:
              "Your meta description is too long <strong>(The ideal length is between 50 and 300 characters)</strong>"
          };
          break;
      }
      return validation;
    }
  }
});

/**
 * Google search preview fieldtype component
 */
Vue.component("seo_box-meta_preview-fieldtype", {
  mixins: [Fieldtype],

  props: ["data", "config", "name"],

  data: function() {
    let slug = this.$parent.$parent.data.slug;
    const url = window.location.origin + (slug ? " › " + slug : "");

    return {
      metaTitle: metaBus.title,
      loading: true,
      url,
      metaDescription: metaBus.description
    };
  },

  created: function() {
    metaBus.$watch("title", title => {
      this.metaTitle = title;
    });
    metaBus.$watch("loading", loading => {
      this.loading = loading;
    });
    metaBus.$watch("description", description => {
      this.metaDescription = description;
    });
  },

  template: `
    <div class="google-styles meta-preview__preview w-3/4 z-depth-1">
        <div v-if="loading">
          <div class="loading loading-basic">
            <span class="icon icon-circular-graph animation-spin"></span> {{ translate('cp.loading') }}
          </div>
        </div>
        <div v-else>
          <a class="google-styles__title">{{ metaTitle }}</a>
          <span class="google-styles__link">{{ url }}</span>
          <p class="google-styles__description">{{ metaDescription }}</p>
        </div>
    </div>
  `
});
