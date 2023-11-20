<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <card-component title="Busca de marcas">
                    <template v-slot:content>
                        <div class="form-row">
                            <div class="col mb-3">
                                <input-container-component
                                    title="ID"
                                    id="inputId"
                                    id-help="idHelp"
                                    text-help="Opcional. Informe o ID da marca"
                                >
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="inputId"
                                        aria-describedby="idHelp"
                                        placeholder="ID"
                                        v-model="search.id"
                                    />
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component
                                    title="Nome"
                                    id="inputNome"
                                    id-help="nomeHelp"
                                    text-help="Opcional. Informe o nome da marca"
                                >
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="nomeHelp"
                                        aria-describedby="nomeHelp"
                                        placeholder="Nome da marca"
                                        v-model="search.name"
                                    />
                                </input-container-component>
                            </div>
                        </div>
                    </template>
                    <template v-slot:footer>
                        <button
                            type="submit"
                            class="btn btn-primary btn-sm float-right"
                            @click="toLookFor()"
                        >
                            Pesquisar
                        </button>
                    </template>
                </card-component>

                <card-component title="Relação de marcas">
                    <template v-slot:content>
                        <table-component
                            :data="brands.data"
                            :view="{
                                visible: true,
                                dataToggle: 'modal',
                                dataTarget: '#modalBrandView'
                            }"
                            :update="true"
                            :remove="true"
                            :title="{
                                id: { title: 'ID', type: 'text' },
                                name: { title: 'Nome', type: 'text' },
                                image: { title: 'Imagem', type: 'image' },
                                created_at: {
                                    title: 'Data de criação',
                                    type: 'date',
                                },
                            }"
                        ></table-component>
                    </template>
                    <template v-slot:footer>
                        <div class="row">
                            <div class="col-10">
                                <paginate-component>
                                    <li
                                        v-for="(l, key) in brands.links"
                                        :key="key"
                                        :class="
                                            l.active
                                                ? 'page-item active'
                                                : 'page-item'
                                        "
                                        @click="pagination(l)"
                                    >
                                        <a
                                            class="page-link"
                                            v-html="l.label"
                                        ></a>
                                    </li>
                                </paginate-component>
                            </div>
                            <div class="col">
                                <button
                                    type="button"
                                    class="btn btn-primary btn-sm float-right"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalBrand"
                                >
                                    Adicionar
                                </button>
                            </div>
                        </div>
                    </template>
                </card-component>
            </div>
        </div>
        <modal-component id="modalBrand" title="Adicionar marca">
            <template v-slot:alerts>
                <alert-component
                    type="success"
                    v-if="status === 'adicionado'"
                    :msg="details"
                    title="Cadastro realizado com sucesso!"
                ></alert-component>
                <alert-component
                    type="danger"
                    v-if="status === 'erro'"
                    :msg="details"
                    title="Erro ao tentar cadastrar a marca"
                ></alert-component>
            </template>
            <template v-slot:content>
                <div class="form-group">
                    <input-container-component
                        title="Nome da marca"
                        id="novoNome"
                        id-help="novoNomeHelp"
                        text-help="Informe o nome da marca"
                    >
                        <input
                            type="text"
                            class="form-control"
                            id="novoNome"
                            aria-describedby="novoNomeHelp"
                            placeholder="Nome da marca"
                            v-model="nameBrand"
                        />
                    </input-container-component>
                </div>
                <div class="form-group">
                    <input-container-component
                        title="Imagem"
                        id="novoImagem"
                        id-help="novoImagemHelp"
                        text-help="Selecione uma imagem no formato PNG"
                    >
                        <input
                            type="file"
                            class="form-control"
                            id="novoImagem"
                            aria-describedby="novoImagemHelp"
                            placeholder="Selecione uma imagem"
                            @change="loadingImage($event)"
                        />
                    </input-container-component>
                </div>
            </template>
            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary" @click="save()">
                    Salvar
                </button>
            </template>
        </modal-component>

        <modal-component id="modalBrandView" title="Visualizar marca">
            <template v-slot:alerts></template>
            <template v-slot:content>Teste</template>
            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </template>
        </modal-component>
    </div>
</template>

<script>
export default {
    computed: {
        token() {
            let token = document.cookie.split(";").find((index) => {
                return index.includes("token=");
            });

            return "Bearer " + token.split("=")[1];
        },
    },
    data() {
        return {
            urlBase: "http://localhost/api/v1/brand",
            urlPaginate: "",
            urlFilter: "",
            nameBrand: "",
            imageBrand: [],
            status: "",
            details: {},
            brands: {
                data: [],
            },
            search: {
                id: "",
                name: "",
            },
        };
    },
    methods: {
        toLookFor() {
            let filter = "";
            for (let keys in this.search) {
                if (this.search[keys]) {
                    if (filter != "") {
                        filter += ";";
                    }
                    filter += keys + ":like:" + this.search[keys];
                }
            }
            if (filter != "") {
                this.urlPaginate = "page=1";
                this.urlFilter = "&filter=" + filter;
            } else {
                this.urlFilter = "";
            }
            this.loadingList();
        },
        pagination(l) {
            if (l.url) {
                this.urlPaginate = l.url.split("?")[1];
                this.loadingList();
            }
        },
        loadingList() {
            let url = this.urlBase + "?" + this.urlPaginate + this.urlFilter;
            let config = {
                headers: {
                    Accept: "application/json",
                    Authorization: this.token,
                },
            };

            axios
                .get(url, config)
                .then((response) => {
                    this.brands = response.data;
                })
                .catch((errors) => {
                    console.log(errors);
                });
        },
        loadingImage(e) {
            this.imageBrand = e.target.files;
        },
        save() {
            let formData = new FormData();
            formData.append("name", this.nameBrand);
            formData.append("image", this.imageBrand[0]);

            let config = {
                headers: {
                    "Content-Type": "multipart/form-data",
                    Accept: "application/json",
                    Authorization: this.token,
                },
            };

            axios
                .post(this.urlBase, formData, config)
                .then((response) => {
                    this.status = "adicionado";
                    this.details = {
                        message: "ID do registro " + response.data.id,
                    };
                })
                .catch((errors) => {
                    this.status = "erro";
                    this.details = {
                        message: errors.response.data.message,
                        data: errors.response.data.errors,
                    };
                });
        },
    },
    mounted() {
        this.loadingList();
    },
};
</script>
