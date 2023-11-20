<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" v-for="(t, key) in title" :key="key">
                        {{ t.title }}
                    </th>
                    <th v-if="view.visible || update.visible || remove.visible"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(obj, key) in dataFilter" :key="key">
                    <td v-for="(value, chaveValor) in obj" :key="chaveValor">
                        <span v-if="title[chaveValor].type == 'text'">{{
                            value
                        }}</span>
                        <span v-if="title[chaveValor].type == 'date'">{{
                            "..." + value
                        }}</span>
                        <span v-if="title[chaveValor].type == 'image'">
                            <img
                                :src="'/storage/' + value"
                                width="30"
                                height="30"
                            />
                        </span>
                    </td>
                    <td v-if="view.visible || update.visible || remove.visible">
                        <button
                            v-if="view.visible"
                            class="btn btn-outline-primary btn-sm"
                            :data-bs-toggle="view.dataToggle"
                            :data-bs-target="view.dataTarget"
                            @click="setStore(obj)"
                        >
                            Visualizar
                        </button>
                        <button
                            v-if="update.visible"
                            class="btn btn-outline-primary btn-sm"
                            :data-bs-toggle="update.dataToggle"
                            :data-bs-target="update.dataTarget"
                            @click="setStore(obj)"

                        >
                            Atualizar
                        </button>
                        <button
                            v-if="remove.visible"
                            class="btn btn-outline-danger btn-sm"
                            :data-bs-toggle="remove.dataToggle"
                            :data-bs-target="remove.dataTarget"
                            @click="setStore(obj)"
                        >
                            Remove
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: ["data", "title", "view", "update", "remove"],
    methods: {
        setStore(obj) {
            this.$store.state.transaction.status = "";
            this.$store.state.transaction.message = "";
            this.$store.state.transaction.data = "";
            this.$store.state.item = obj;
        },
    },
    computed: {
        dataFilter() {
            let fields = Object.keys(this.title);
            let dataFilter = [];
            this.data.map((item, key) => {
                let itemFilter = {};
                fields.forEach((field) => {
                    itemFilter[field] = item[field];
                });
                dataFilter.push(itemFilter);
            });
            return dataFilter;
        },
    },
};
</script>
