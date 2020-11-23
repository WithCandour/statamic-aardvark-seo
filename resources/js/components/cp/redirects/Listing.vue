<template>
    <data-list :columns="columns" :sort="false" :rows="rows">
        <div class="card p-0" slot-scope="{ filteredRows: rows }">
            <data-list-table :rows="rows">
                <template slot="cell-id" slot-scope="{ row: redirect }">
                    <a :href="redirect.edit_url">{{ redirect.id }}</a>
                </template>
                <template slot="actions" slot-scope="{ row: redirect }">
                    <dropdown-list>
                        <dropdown-item :text="__('Edit')" :redirect="redirect.edit_url" />
                        <dropdown-item
                            :text="__('Delete')" :redirect="redirect.delete_url"
                            class="warning"
                            @click="$refs[`deleter_${redirect.id}`].confirm()"
                        >
                            <resource-deleter
                                :ref="`deleter_${redirect.id}`"
                                :resource="redirect"
                            ></resource-deleter>
                        </dropdown-item>
                    </dropdown-list>
                </template>
            </data-list-table>
        </div>
    </data-list>
</template>

<script>
    export default {

        props: [
            'initial-redirects',
            'columns',
        ],

        data() {
            return {
                rows: this.initialRedirects
            }
        }

    }
</script>
