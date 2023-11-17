<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <card-component title="Busca de marcas">
                    <template v-slot:content>
                        <div class="form-row">
                            <div class="col mb-3">
                                <input-container-component title="ID" id="inputId" id-help="idHelp" text-help="Opcional. Informe o ID da marca" >
                                    <input type="number" class="form-control" id="inputId" aria-describedby="idHelp" placeholder="ID" />
                                </input-container-component>
                            </div>
                            <div class="col mb-3">
                                <input-container-component title="Nome" id="inputNome" id-help="nomeHelp" text-help="Opcional. Informe o nome da marca" >
                                    <input type="text" class="form-control" id="nomeHelp" aria-describedby="nomeHelp" placeholder="Nome da marca"/>
                                </input-container-component>
                            </div>
                        </div>
                    </template>
                    <template v-slot:footer>
                        <button type="submit" class="btn btn-primary btn-sm float-right" >
                            Pesquisar
                        </button>
                    </template>
                </card-component>

                <card-component title="Relação de marcas">
                    <template v-slot:content>
                        <table-component></table-component>
                    </template>
                    <template v-slot:footer>
                        <button type="button" class="btn btn-primary btn-sm float-right" data-bs-toggle="modal" data-bs-target="#modalBrand">
                            Adicionar
                        </button>
                    </template>
                </card-component>
            </div>
        </div>
        <modal-component id="modalBrand" title="Adicionar marca">
            <template v-slot:content>
                <div class="form-group">
                    <input-container-component title="Nome da marca" id="novoNome" id-help="novoNomeHelp" text-help="Informe o nome da marca" >
                        <input type="text" class="form-control" id="novoNome" aria-describedby="novoNomeHelp" placeholder="Nome da marca"/>
                    </input-container-component>
                </div>
                <div class="form-group">
                    <input-container-component title="Imagem" id="novoImagem" id-help="novoImagemHelp" text-help="Selecione uma imagem no formato PNG" >
                        <input type="file" class="form-control-file " id="novoImagem" aria-describedby="novoImagemHelp" placeholder="Selecione uma imagem"/>
                    </input-container-component>
                </div>
            </template>
            <template v-slot:footer>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary">Salvar</button>
            </template>
        </modal-component>
    </div>
</template>

<script></script>
