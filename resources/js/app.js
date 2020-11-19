import MetaTitleFieldtype from './components/fieldtypes/MetaTitleFieldtype';
import MetaDescriptionFieldtype from './components/fieldtypes/MetaDescriptionFieldtype';
import GooglePreviewFieldtype from './components/fieldtypes/GooglePreviewFieldtype';

Statamic.booting(() => {
    Statamic.component('aardvark_seo_meta_title-fieldtype', MetaTitleFieldtype);
    Statamic.component('aardvark_seo_meta_description-fieldtype', MetaDescriptionFieldtype);
    Statamic.component('aardvark_seo_google_preview-fieldtype', GooglePreviewFieldtype);
});
