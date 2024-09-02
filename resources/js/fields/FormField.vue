<script>
import { DependentFormField, HandlesValidationErrors } from 'laravel-nova';
import ResourceCard from '../components/resourceCard.vue';
export default {
    mixins: [HandlesValidationErrors, DependentFormField],
    inject: ['removeFile'],
    expose: ['beforeRemove'],
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
            loading: false,
        }
    },
    methods: {
        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            try {
                const attr = this.fieldAttribute
                console.log(this.value)
                for (const v in this.value) {
                    formData.append(`${attr}[]`, this.value[v])
                }
            } catch (e) {
                console.log(e)
            }
        },
    }
}
</script>

<template>
    <div
        class="ns-flex ns-flex-row ns-flex-nowrap sm:ns-grid sm:ns-grid-cols-3 ns-w-full ns-gap-4 sm:ns-max-h-[70dvh] ns-scroll-smooth ns-snap-x sm:ns-snap-y ns-snap-mandatory sm:ns-overflow-y-auto py-1 pr-4 ns-overflow-y-auto">
        <template v-for="resource in resources">
            <ResourceCard :resource="resource" v-model="value" />
        </template>
    </div>
</template>
