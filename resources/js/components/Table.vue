<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" v-for="(t, key) in title" :key="key">
                        {{ t.title }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(obj, key) in dataFilter" :key="key">
                    <td v-for="(value, chaveValor) in obj" :key="chaveValor">
                        <span v-if="title[chaveValor].type == 'text'">{{value}}</span>
                        <span v-if="title[chaveValor].type == 'date'">{{ "..." + value }}</span>
                        <span v-if="title[chaveValor].type == 'image'">
                            <img
                                :src="'/storage/' + value"
                                width="30"
                                height="30"
                            />
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: ["data", "title"],
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
