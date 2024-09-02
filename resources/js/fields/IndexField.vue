<script>
export default {
    props: ['resourceName', 'resourceId', 'field'],
    computed: {
        resources() {
            return this.field.resources;
        },
    },
    data() {
        return {
            value: this.field.value,
        }
    }
}
</script>

<template>
    <span>{{ value.length }}</span>
</template>
