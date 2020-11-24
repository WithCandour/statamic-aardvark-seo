<template>
    <publish-form
        :title="title"
        :action="action"
        :blueprint="blueprint"
        :meta="meta"
        :method="method"
        :values="values"
    ></publish-form>
</template>
<script>
    export default {
        props: [
            'title',
            'action',
            'blueprint',
            'method',
            'meta',
            'values',
        ],

        methods: {
            redirectOnSaved() {
                // TODO: make this redirect after an entry is created
                // (redirecting on success works but the dirty state needs to be cleared)
            }
        }
    }
</script>
