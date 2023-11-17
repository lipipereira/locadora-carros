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
                                    />
                                </input-container-component>
                            </div>
                        </div>
                    </template>
                    <template v-slot:footer>
                        <button
                            type="submit"
                            class="btn btn-primary btn-sm float-right"
                        >
                            Pesquisar
                        </button>
                    </template>
                </card-component>

                <card-component title="Relação de marcas">
                    <template v-slot:content>
                        <table-component></table-component>
                    </template>
                    <template v-slot:footer>
                        <button
                            type="button"
                            class="btn btn-primary btn-sm float-right"
                            data-bs-toggle="modal"
                            data-bs-target="#modalBrand"
                        >
                            Adicionar
                        </button>
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
                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Fechar
                </button>
                <button type="button" class="btn btn-primary" @click="save()">
                    Salvar
                </button>
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
            nameBrand: "",
            imageBrand: [],
            status: "",
            details: [],
        };
    },
    methods: {
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
                    this.details = response.response;
                })
                .catch((errors) => {
                    this.status = "erro";
                    this.details = errors.response;
                });
        },
    },
};
</script>
