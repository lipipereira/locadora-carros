<template>
    <div :class="style" role="alert">
        {{ title }}
        <hr />
        <p>{{ message.message }}</p>
        <br />
        <ul v-if="message.data">
            <li v-for="(e, key) in message.data" :key="key">{{ e[0] }}</li>
        </ul>
    </div>
</template>

<script>
export default {
    props: ["type", "message", "title"],
    computed: {
        style() {
            return "alert alert-" + this.type;
        },
    },
};
</script>
