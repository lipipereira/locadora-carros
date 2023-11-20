<template>
    <div :class="style" role="alert">
        {{ title }}
        <hr />
        <p>{{ msg.message }}</p>
        <br />
        <ul v-if="msg.data">
            <li v-for="(e, key) in msg.data" :key="key">{{ e[0] }}</li>
        </ul>
    </div>
</template>

<script>
export default {
    props: ["type", "msg", "title"],
    computed: {
        style() {
            return "alert alert-" + this.type;
        },
    },
};
</script>
