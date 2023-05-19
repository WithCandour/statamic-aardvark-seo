<template>
    <data-list :columns="columns" :rows="rows" v-if="rows.length">
        <div class="card p-0 relative" slot-scope="{ filteredRows: rows }">
            <data-list-bulk-actions
                :url="bulkActionsUrl"
                @started="actionStarted"
                @completed="actionCompleted"
            />
            <data-list-table
                :rows="rows"
                :allow-bulk-actions="true"
            >
                <template slot="cell-source_url" slot-scope="{ row: redirect }">
                    <a :href="redirect.edit_url">{{ redirect.source_url }}</a>
                </template>
                <template slot="cell-is_active" slot-scope="{ row: redirect }">
                    {{ redirect.is_active ? 'Yes' : 'No' }}
                </template>
                <template slot="actions" slot-scope="{ row: redirect }">
                    <dropdown-list>
                        <dropdown-item :text="__('Edit')" :redirect="redirect.edit_url" />
                        <dropdown-item
                            :text="__('Delete')"
                            class="warning"
                            @click="$refs[`deleter_${redirect.id}`].confirm()"
                        >
                            <resource-deleter
                                :ref="`deleter_${redirect.id}`"
                                :resource="redirect"
                                @deleted="removeRow(redirect)"
                            ></resource-deleter>
                        </dropdown-item>
                    </dropdown-list>
                </template>
            </data-list-table>
        </div>
    </data-list>
    <div v-else class="md:pt-16 max-w-2xl mx-auto">
        <div class="w-full md:w-1/2">
            <h1 class="mb-8">Redirects</h1>
            <p class="text-gray-700 leading-normal mb-8 text-lg antialiased">
                Redirects are used to direct users to content which may have been removed or deleted.
            </p>
            <a :href="this.createUrl" class="btn-primary btn-lg">Create a redirect</a>
        </div>
    </div>
</template>

<script>
    export default {

        mixins: [Listing],

        props: [
            'initial-redirects',
            'initial-columns',
            'create-url',
        ],

        data() {
            return {
                rows: this.initialRedirects,
                columns: this.initialColumns
            }
        },

        methods: {
            actionCompleted() {
                location.reload();
            }
        },

        computed: {

        }

    }
</script>
