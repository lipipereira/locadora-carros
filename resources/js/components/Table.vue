<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" v-for="(t, key) in title" :key="key">
                        {{ t.title }}
                    </th>
                    <th v-if="view.visible || update || remove"></th>
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
                    <td v-if="view.visible || update || remove">
                        <button
                            v-if="view.visible"
                            class="btn btn-outline-primary btn-sm"
                            :data-bs-toggle="view.dataToggle"
                            :data-bs-target="view.dataTarget"
                        >
                            Visualizar
                        </button>
                        <button
                            v-if="update"
                            class="btn btn-outline-primary btn-sm"
                        >
                            Atualizar
                        </button>
                        <button
                            v-if="remove"
                            class="btn btn-outline-danger btn-sm"
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
