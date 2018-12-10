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

Vue.component("seo_box-valid_meta_title-fieldtype", {
  mixins: [Fieldtype, MetaAnalysis],

  props: ["data", "config", "name"],

  template: `
    <div class="meta_text_validator--outer">
      <div class="meta_text_validator--field-container">
        <input v-on:keyup="handleKeyUp" :type="mode" class="form-control" v-model="data" tabindex="0" :autofocus="autofocus" :placeholder="config.placeholder" />
        <progress max="70" :value="contentLength" :class="'meta_text_validator--progress meta_text_validator--progress--' + validation.step"></progress>
      </div>
      <span class="meta_text_validator--caption" v-html="validation.caption"></span>
    </div>
  `,

  methods: {
    _getValidation: function(length) {
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
          validation = { step: "err", caption: "Your meta title is too long <strong>the ideal length is between 20 and 70 characters</strong>" };
          break;
      }
      return validation;
    }
  }
});

Vue.component("seo_box-valid_meta_description-fieldtype", {
  mixins: [Fieldtype, MetaAnalysis],

  props: ["data", "config", "name"],

  template: `
    <div class="meta_text_validator--outer">
      <div class="meta_text_validator--field-container">
        <textarea v-on:keyup="handleKeyUp" class="form-control" v-model="data" v-el:textarea v-elastic :placeholder="config.placeholder"></textarea>
        <progress max="300" :value="contentLength" :class="'meta_text_validator--progress meta_text_validator--progress--' + validation.step"></progress>
      </div>
      <span class="meta_text_validator--caption" v-html="validation.caption"></span>
    </div>
  `,

  methods: {
    _getValidation: function(length) {
      let validation;
      switch (true) {
        case length === 0:
          validation = {
            step: "valid",
            caption:
              "You have not set a meta description for this page"
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
          validation = { step: "err", caption: "Your meta description is too long <strong>(The ideal length is between 50 and 300 characters)</strong>" };
          break;
      }
      return validation;
    }
  }
});
