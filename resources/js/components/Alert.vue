<template>
    <div :class="style" role="alert">
        {{ title }}
        <hr />
        <span v-if="msg.data.message">{{ msg.data.message }}</span>
        <span v-if="msg.data.id">{{ "ID do registro " + msg.data.id }}</span>
        <br />
        <ul v-if="msg.data.errors">
            <li v-for="(e, key) in msg.data.errors" :key="key">{{ e[0] }}</li>
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
