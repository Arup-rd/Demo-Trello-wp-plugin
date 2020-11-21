<template>
    <div> 
        <h1>{{task.name}}</h1>
    </div>
</template>

<script>

    import { mapGetters } from 'vuex'

    export default {
        computed: {
            ...mapGetters(['getTask']),
            task () {
                return this.getTask(this.$route.params.id)
            }
        }
    }
</script>

<style lang="scss" scoped>
</style>
