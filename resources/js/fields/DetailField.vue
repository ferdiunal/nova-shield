<script>
import ResourceCard from '../components/resourceCard.vue';
export default {
    props: ['resourceName', 'resourceId', 'field'],
    components: {
        ResourceCard,
    },
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
    <div
        class="ns-flex ns-flex-row ns-flex-nowrap sm:ns-grid sm:ns-grid-cols-3 ns-w-full ns-gap-4 sm:ns-max-h-[70dvh] ns-scroll-smooth ns-snap-x sm:ns-snap-y ns-snap-mandatory sm:ns-overflow-y-auto py-1 pr-4 ns-overflow-y-auto">
        <template v-for="resource in resources">
            <ResourceCard :disabled="true" :resource="resource" v-model="value" />
        </template>
    </div>
</template>
