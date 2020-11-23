import MetaTitleFieldtype from './components/fieldtypes/MetaTitleFieldtype';
import MetaDescriptionFieldtype from './components/fieldtypes/MetaDescriptionFieldtype';
import GooglePreviewFieldtype from './components/fieldtypes/GooglePreviewFieldtype';
import RedirectsListing from './components/cp/redirects/Listing';
import RedirectsPublishForm from './components/cp/redirects/PublishForm';

Statamic.booting(() => {
    // Fieldtypes
    Statamic.component('aardvark_seo_meta_title-fieldtype', MetaTitleFieldtype);
    Statamic.component('aardvark_seo_meta_description-fieldtype', MetaDescriptionFieldtype);
    Statamic.component('aardvark_seo_google_preview-fieldtype', GooglePreviewFieldtype);

    // Redirects components
    Statamic.component('aardvark-redirects-listing', RedirectsListing);
    Statamic.component('aardvark-redirects-publish-form', RedirectsPublishForm);
});
