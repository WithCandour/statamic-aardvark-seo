<script>
    export default {
        data() {
            const contentLength = typeof this.value === 'string' ? this.value.length : 0;
            return {
                contentLength,
                validation: this.validateMeta(contentLength)
            };
        },

        methods: {
            handleKeyUp() {
                this.contentLength = this.value && this.value.length;
                this.validation = this.validateMeta(this.value && this.value.length);
            }
        }
    }
</script>
