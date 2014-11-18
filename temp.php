<?php

$arr = array(1,2,3,4,5);

echo implode("','",$arr);

echo sha1('$d1a9r3a6');
exit();
?>
<form class="tabs" method="post" action="" enctype="application/x-www-form-urlencoded" name="cadernetaform" id="cadernetaform" style="display: block;"><ul class="ui-tabs ui-widget ui-widget-content ui-corner-all ui-tabs-vertical ui-helper-clearfix"><li class="nav-tabs ui-corner-left"><ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist"><li class="selected ui-state-default ui-corner-top ui-tabs-active ui-state-active ui-tabs-loading" role="tab" tabindex="0" aria-controls="ui-tabs-1" aria-labelledby="ui-id-1" aria-selected="true"><a href="#fieldset-DataObra" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-1"><span>1 - Dados da Obra</span></a></li><li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="ui-tabs-2" aria-labelledby="ui-id-2" aria-selected="false"><a href="#fieldset-DataProprietario" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-2"><span>2 - Dados do Proprietário</span></a></li><li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="ui-tabs-3" aria-labelledby="ui-id-3" aria-selected="false"><a href="#fieldset-DataEnderecoProprietario" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-3"><span>3 - Endereço do Proprietário</span></a></li><li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="ui-tabs-4" aria-labelledby="ui-id-4" aria-selected="false"><a href="#fieldset-DataTecnico" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-4"><span>4 - Autor do Projeto</span></a></li><li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="ui-tabs-5" aria-labelledby="ui-id-5" aria-selected="false"><a href="#fieldset-DataCreaCalUser" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-5"><span>5 - Área de Construção</span></a></li><li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="ui-tabs-6" aria-labelledby="ui-id-6" aria-selected="false"><a href="#fieldset-DataPagament" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-6"><span>6 - Pagamento</span></a></li></ul><div id="ui-tabs-1" class="ui-tabs-panel ui-widget-content ui-corner-bottom" aria-live="polite" aria-labelledby="ui-id-1" role="tabpanel" aria-expanded="true" aria-hidden="false" aria-busy="true">
                

            </div><div id="ui-tabs-2" class="ui-tabs-panel ui-widget-content ui-corner-bottom" aria-live="polite" aria-labelledby="ui-id-2" role="tabpanel" style="display: none;" aria-expanded="false" aria-hidden="true"></div><div id="ui-tabs-3" class="ui-tabs-panel ui-widget-content ui-corner-bottom" aria-live="polite" aria-labelledby="ui-id-3" role="tabpanel" style="display: none;" aria-expanded="false" aria-hidden="true"></div><div id="ui-tabs-4" class="ui-tabs-panel ui-widget-content ui-corner-bottom" aria-live="polite" aria-labelledby="ui-id-4" role="tabpanel" style="display: none;" aria-expanded="false" aria-hidden="true"></div><div id="ui-tabs-5" class="ui-tabs-panel ui-widget-content ui-corner-bottom" aria-live="polite" aria-labelledby="ui-id-5" role="tabpanel" style="display: none;" aria-expanded="false" aria-hidden="true"></div><div id="ui-tabs-6" class="ui-tabs-panel ui-widget-content ui-corner-bottom" aria-live="polite" aria-labelledby="ui-id-6" role="tabpanel" style="display: none;" aria-expanded="false" aria-hidden="true"></div></li>
        <li class="tab ui-corner-left" style="display: list-item;"><fieldset id="fieldset-DataObra"><legend>1 - Dados da Obra</legend>
                <ul>
                    <li><label class="optional" for="numero_caderneta">Caderneta</label>

                        <input type="text" class="" value="" id="numero_caderneta" name="numero_caderneta">
                        <p class="hint">Número da caderneta, se não for preenchido será atribuido um automaticamente.</p></li>
                    <li><label class="optional" for="sequencia">Em sequencia</label>

                        <input type="text" class="" value="" id="sequencia" name="sequencia">
                        <p class="hint">Preencha se for uma sequencia de outra caderneta.</p></li>
                    <li><label class="required" for="art">ART/RRT nº</label>

                        <input type="text" class="required" value="" id="art" name="art"></li>
                    <li><label class="required" for="tipo_obra">Tipo de obra</label>

                        <select class="required" id="tipo_obra" name="tipo_obra">
                            <option selected="selected" label="--" value="">--</option>
                            <option label="Casa" value="casa">Casa</option>
                            <option label="Galpão" value="galpao">Galpão</option>
                            <option label="Outros" value="outros">Outros</option>
                            <option label="Ponto Comercial" value="ponto-comercial">Ponto Comercial</option>
                            <option label="Prédio" value="predio">Prédio</option>
                            <option label="Sobrado" value="sobrado">Sobrado</option>
                        </select></li>
                    <li><label class="required" for="uso_predominante">Uso predominante</label>

                        <select class="required" id="uso_predominante" name="uso_predominante">
                            <option selected="selected" label="--" value="">--</option>
                            <option label="Comercial" value="comercial">Comercial</option>
                            <option label="Hospitalar" value="hospitalar">Hospitalar</option>
                            <option label="Industrial" value="industrial">Industrial</option>
                            <option label="Misto" value="misto">Misto</option>
                            <option label="Outros" value="outros">Outros</option>
                            <option label="Residencial" value="residencial">Residencial</option>
                        </select></li>
                    <li><label class="required" for="obra_cep">CEP</label>

                        <input type="hidden" value="-22.546052,-48.635514" name="obra_cep_coord" id="obra_cep_coord"><input type="text" latlng="-22.546052,-48.635514" cep_url="http://republicavirtual.com.br/web_cep.php" maps_url="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=" sensor="false" default_zoom="6" zoom="23" title="CEP obrigatório." fil="#fieldset-DataObra " alt="cep" class="required cep" value="" id="obra_cep" name="obra_cep"></li>
                    <li><label class="required" for="obra_rua">Rua</label>

                        <input type="text" class="required endereco" value="" id="obra_rua" name="obra_rua"></li>
                    <li><label class="optional" for="obra_numero">Número</label>

                        <input type="text" class="numero" value="" id="obra_numero" name="obra_numero"></li>
                    <li><label class="optional" for="obra_complemento">Complemento</label>

                        <input type="text" class="complemento" value="" id="obra_complemento" name="obra_complemento"></li>
                    <li><label class="optional" for="obra_quadra">Quadra</label>

                        <input type="text" value="" id="obra_quadra" name="obra_quadra"></li>
                    <li><label class="optional" for="obra_lote">Lote</label>

                        <input type="text" value="" id="obra_lote" name="obra_lote"></li>
                    <li><label class="optional" for="obra_inscricao">Inscrição imobiliária</label>

                        <input type="text" value="" id="obra_inscricao" name="obra_inscricao"></li>
                    <li><label class="required" for="obra_bairro">Bairro</label>

                        <input type="text" class="required bairro" value="" id="obra_bairro" name="obra_bairro"></li></ul></fieldset></li>
        <li class="tab ui-corner-left" style="display: none;"><fieldset id="fieldset-DataProprietario"><legend>2 - Dados do Proprietário</legend>
                <ul>
                    <li><label class="required" for="proprietario">Proprietário</label>

                        <input type="text" class="required" value="" id="proprietario" name="proprietario"></li>
                    <li><label class="required" for="proprietario_cpf_cnpj">CPF/CNPJ</label>

                        <input type="text" alt="" class="required" value="" id="proprietario_cpf_cnpj" name="proprietario_cpf_cnpj"></li>
                    <li><label class="required" for="proprietario_telefone_1">Telefone</label>

                        <input type="text" alt="tel" role="tel" class="tel required" value="" id="proprietario_telefone_1" name="proprietario_telefone_1">
                        <p class="hint">Formato (00) 0000-0000</p></li>
                    <li><label class="required" for="proprietario_telefone_tipo_1">Tipo telefone</label>

                        <select class="required" id="proprietario_telefone_tipo_1" name="proprietario_telefone_tipo_1">
                            <option selected="selected" label="--" value="">--</option>
                            <option label="Celular" value="celular">Celular</option>
                            <option label="Comercial" value="comercial">Comercial</option>
                            <option label="Consultório" value="consultorio">Consultório</option>
                            <option label="Contato" value="contato">Contato</option>
                            <option label="Escriitório" value="escriitorio">Escriitório</option>
                            <option label="Filial" value="filial">Filial</option>
                            <option label="Matriz" value="matriz">Matriz</option>
                            <option label="Recados" value="recados">Recados</option>
                            <option label="Residencial" value="residencial">Residencial</option>
                        </select>
                        <p class="hint">Tipo de telefone acima.</p></li>
                    <li><label class="optional" for="proprietario_telefone_2">Telefone</label>

                        <input type="text" alt="tel" role="tel" class="tel" value="" id="proprietario_telefone_2" name="proprietario_telefone_2">
                        <p class="hint">Formato (00) 0000-0000</p></li>
                    <li><label class="optional" for="proprietario_telefone_tipo_2">Tipo telefone</label>

                        <select id="proprietario_telefone_tipo_2" name="proprietario_telefone_tipo_2">
                            <option selected="selected" label="--" value="">--</option>
                            <option label="Celular" value="celular">Celular</option>
                            <option label="Comercial" value="comercial">Comercial</option>
                            <option label="Consultório" value="consultorio">Consultório</option>
                            <option label="Contato" value="contato">Contato</option>
                            <option label="Escriitório" value="escriitorio">Escriitório</option>
                            <option label="Filial" value="filial">Filial</option>
                            <option label="Matriz" value="matriz">Matriz</option>
                            <option label="Recados" value="recados">Recados</option>
                            <option label="Residencial" value="residencial">Residencial</option>
                        </select>
                        <p class="hint">Tipo de telefone acima.</p></li></ul></fieldset></li>
        <li class="tab ui-corner-left" style="display: none;"><fieldset id="fieldset-DataEnderecoProprietario"><legend>3 - Endereço do Proprietário</legend>
                <ul>
                    <li><label class="required" for="proprietario_endereco_obra">Endereço</label>

                        <select class="required endereco_prop" id="proprietario_endereco_obra" name="proprietario_endereco_obra">
                            <option label="O endereço da obra e do proprietário são DIFERENTES." value="1">O endereço da obra e do proprietário são DIFERENTES.</option>
                            <option label="O endereço da obra e do proprietário são IGUAIS." value="0">O endereço da obra e do proprietário são IGUAIS.</option>
                        </select></li>
                    <li><label class="required" for="proprietario_endereco_tipo">Tipo do endereço</label>

                        <select class="required" id="proprietario_endereco_tipo" name="proprietario_endereco_tipo">
                            <option selected="selected" label="--" value="">--</option>
                            <option label="Comercial" value="comercial">Comercial</option>
                            <option label="Consultório" value="consultorio">Consultório</option>
                            <option label="Escritório" value="escritorio">Escritório</option>
                            <option label="Filial" value="filial">Filial</option>
                            <option label="Hotel" value="hotel">Hotel</option>
                            <option label="Matriz" value="matriz">Matriz</option>
                            <option label="Outro" value="outro">Outro</option>
                            <option label="Regularização" value="regularizacao">Regularização</option>
                            <option label="Residencial" value="residencial">Residencial</option>
                        </select></li>
                    <li><label class="required" for="proprietario_cep">CEP</label>

                        <input type="hidden" value="-22.546052,-48.635514" name="proprietario_cep_coord" id="proprietario_cep_coord"><input type="text" latlng="-22.546052,-48.635514" cep_url="http://republicavirtual.com.br/web_cep.php" maps_url="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=" sensor="false" default_zoom="6" zoom="23" title="CEP obrigatório." fil="#fieldset-DataEnderecoProprietario " alt="cep" class="required cep" value="" id="proprietario_cep" name="proprietario_cep"></li>
                    <li><label class="required" for="proprietario_rua">Rua</label>

                        <input type="text" class="required endereco" value="" id="proprietario_rua" name="proprietario_rua"></li>
                    <li><label class="optional" for="proprietario_numero">Número</label>

                        <input type="text" class="numero" value="" id="proprietario_numero" name="proprietario_numero"></li>
                    <li><label class="optional" for="proprietario_complemento">Complemento</label>

                        <input type="text" class="complemento" value="" id="proprietario_complemento" name="proprietario_complemento"></li>
                    <li><label class="required" for="proprietario_bairro">Bairro</label>

                        <input type="text" class="required bairro" value="" id="proprietario_bairro" name="proprietario_bairro"></li>
                    <li><label class="required" for="proprietario_cidade">Cidade</label>

                        <input type="text" class="required cidade" value="" id="proprietario_cidade" name="proprietario_cidade"></li>
                    <li><label class="required" for="proprietario_estado">Estado</label>

                        <select class="required estado" id="proprietario_estado" name="proprietario_estado">
                            <option selected="selected" label="--" value="">--</option>
                            <option label="AC" value="AC">AC</option>
                            <option label="AL" value="AL">AL</option>
                            <option label="AM" value="AM">AM</option>
                            <option label="AP" value="AP">AP</option>
                            <option label="BA" value="BA">BA</option>
                            <option label="CE" value="CE">CE</option>
                            <option label="DF" value="DF">DF</option>
                            <option label="ES" value="ES">ES</option>
                            <option label="GO" value="GO">GO</option>
                            <option label="MA" value="MA">MA</option>
                            <option label="MG" value="MG">MG</option>
                            <option label="MS" value="MS">MS</option>
                            <option label="MT" value="MT">MT</option>
                            <option label="PA" value="PA">PA</option>
                            <option label="PB" value="PB">PB</option>
                            <option label="PE" value="pe">PE</option>
                            <option label="PI" value="pi">PI</option>
                            <option label="PR" value="PR">PR</option>
                            <option label="RJ" value="RJ">RJ</option>
                            <option label="RN" value="RN">RN</option>
                            <option label="RO" value="RO">RO</option>
                            <option label="RR" value="RR">RR</option>
                            <option label="RS" value="RS">RS</option>
                            <option label="SC" value="SC">SC</option>
                            <option label="SE" value="SE">SE</option>
                            <option label="SP" value="SP">SP</option>
                            <option label="TO" value="TO">TO</option>
                            <option label="Outros" value="outros">Outros</option>
                        </select></li></ul></fieldset></li>
        <li class="tab ui-corner-left" style="display: none;"><fieldset id="fieldset-DataTecnico"><legend>4 - Autor do Projeto</legend>
                <ul>
                    <li><label class="required" for="user_id">Técnico</label>

                        <select class="required" id="user_id" name="user_id">
                            <option label="--" value="">--</option>
                            <option label="Consultório" value="30">Consultório</option>
                        </select></li>
                    <li>
                        <div class="messages errors" id="messages_tecnico"></div></li>
                    <li><label class="required" for="tecnico_autor">Autor</label>

                        <select class="required tecnico_autor" id="tecnico_autor" name="tecnico_autor">
                            <option label="O Autor do Projeto é DIFERENTE do Responsável Técnico." value="1">O Autor do Projeto é DIFERENTE do Responsável Técnico.</option>
                            <option label="O Autor do Projeto IGUAL ao Responsável Técnico." value="0">O Autor do Projeto IGUAL ao Responsável Técnico.</option>
                        </select></li>
                    <li><label class="required" for="tecnico_nome">Nome</label>

                        <input type="text" class="required nome" value="" id="tecnico_nome" name="tecnico_nome"></li>
                    <li><label class="required" for="tecnico_academic_area">Área de formação</label>

                        <select class="required tecnico_academic_area" id="tecnico_academic_area" name="tecnico_academic_area">
                            <option selected="selected" label="--" value="">--</option>
                            <option label="Aeronautica" value="aeronautica">Aeronautica</option>
                            <option label="Agrimensura" value="agrimensura">Agrimensura</option>
                            <option label="Agronomia" value="agronomia">Agronomia</option>
                            <option label="Arquitetura" value="arquitetura">Arquitetura</option>
                            <option label="Arquitetura e Urbanismo" value="arquitetura-e-urbanismo">Arquitetura e Urbanismo</option>
                            <option label="Cartografia, Geodesia,Topografia" value="cartografia-geodesiatopografia">Cartografia, Geodesia,Topografia</option>
                            <option label="Eletrica Ou Eletrica Mod. Eletrotecnica" value="eletrica-ou-eletrica-mod-eletrotecnica">Eletrica Ou Eletrica Mod. Eletrotecnica</option>
                            <option label="Eletronica Ou Eletrica Mod. Eletronica Ou Comunicacao" value="eletronica-ou-eletrica-mod-eletronica-ou-comunicacao">Eletronica Ou Eletrica Mod. Eletronica Ou Comunicacao</option>
                            <option label="Eng. Agricola" value="eng-agricola">Eng. Agricola</option>
                            <option label="Eng. Ambiental" value="eng-ambiental">Eng. Ambiental</option>
                            <option label="Eng. Civil" value="eng-civil">Eng. Civil</option>
                            <option label="Eng. De Minas" value="eng-de-minas">Eng. De Minas</option>
                            <option label="Eng. De Operacao" value="eng-de-operacao">Eng. De Operacao</option>
                            <option label="Eng. De Pesca" value="eng-de-pesca">Eng. De Pesca</option>
                            <option label="Eng. De Petroleo" value="eng-de-petroleo">Eng. De Petroleo</option>
                            <option label="Eng. Naval" value="eng-naval">Eng. Naval</option>
                            <option label="Eng. Quimica" value="eng-quimica">Eng. Quimica</option>
                            <option label="Eng. Sanitarista" value="eng-sanitarista">Eng. Sanitarista</option>
                            <option label="Eng. Textil" value="eng-textil">Eng. Textil</option>
                            <option label="Florestal" value="florestal">Florestal</option>
                            <option label="Fortificacao E Construção" value="fortificacao-e-construcao">Fortificacao E Construção</option>
                            <option label="Geografia" value="geografia">Geografia</option>
                            <option label="Geologia" value="geologia">Geologia</option>
                            <option label="Mecanica , Mec. Automoveis" value="mecanica-mec-automoveis">Mecanica , Mec. Automoveis</option>
                            <option label="Metalurgia" value="metalurgia">Metalurgia</option>
                            <option label="Meteorologia" value="meteorologia">Meteorologia</option>
                            <option label="Outros" value="outros">Outros</option>
                            <option label="Seguranca Do Trabalho" value="seguranca-do-trabalho">Seguranca Do Trabalho</option>
                            <option label="Tecnica Agricola" value="tecnica-agricola">Tecnica Agricola</option>
                            <option label="Tecnica Industrial" value="tecnica-industrial">Tecnica Industrial</option>
                            <option label="Tecnologia" value="tecnologia">Tecnologia</option>
                            <option label="Tecnologia De Alimentos" value="tecnologia-de-alimentos">Tecnologia De Alimentos</option>
                            <option label="Urbanismo" value="urbanismo">Urbanismo</option>
                        </select></li>
                    <li><label class="required" for="tecnico_art">ART/RRT nº</label>

                        <input type="text" class="required tecnico_art" value="" id="tecnico_art" name="tecnico_art"></li>
                    <li><label class="required" for="tecnico_crea">CREA/CAU</label>

                        <input type="text" class="required crea" value="" id="tecnico_crea" name="tecnico_crea"></li>
                    <li><label class="required" for="tecnico_telefone_1">Telefone</label>

                        <input type="text" alt="tel" role="tel" class="tel telefone_1 required" value="" id="tecnico_telefone_1" name="tecnico_telefone_1">
                        <p class="hint">Formato (00) 0000-0000</p></li>
                    <li><label class="required" for="tecnico_telefone_tipo_1">Tipo telefone</label>

                        <select class="required telefone_tipo_1" id="tecnico_telefone_tipo_1" name="tecnico_telefone_tipo_1">
                            <option selected="selected" label="--" value="">--</option>
                            <option label="Celular" value="celular">Celular</option>
                            <option label="Comercial" value="comercial">Comercial</option>
                            <option label="Consultório" value="consultorio">Consultório</option>
                            <option label="Contato" value="contato">Contato</option>
                            <option label="Escriitório" value="escriitorio">Escriitório</option>
                            <option label="Filial" value="filial">Filial</option>
                            <option label="Matriz" value="matriz">Matriz</option>
                            <option label="Recados" value="recados">Recados</option>
                            <option label="Residencial" value="residencial">Residencial</option>
                        </select>
                        <p class="hint">Tipo de telefone acima.</p></li>
                    <li><label class="optional" for="tecnico_telefone_2">Telefone</label>

                        <input type="text" alt="tel" role="tel" class="tel telefone_2" value="" id="tecnico_telefone_2" name="tecnico_telefone_2"></li>
                    <li><label class="optional" for="tecnico_telefone_tipo_2">Tipo telefone</label>

                        <select class="telefone_tipo_2" id="tecnico_telefone_tipo_2" name="tecnico_telefone_tipo_2">
                            <option selected="selected" label="--" value="">--</option>
                            <option label="Celular" value="celular">Celular</option>
                            <option label="Comercial" value="comercial">Comercial</option>
                            <option label="Consultório" value="consultorio">Consultório</option>
                            <option label="Contato" value="contato">Contato</option>
                            <option label="Escriitório" value="escriitorio">Escriitório</option>
                            <option label="Filial" value="filial">Filial</option>
                            <option label="Matriz" value="matriz">Matriz</option>
                            <option label="Recados" value="recados">Recados</option>
                            <option label="Residencial" value="residencial">Residencial</option>
                        </select>
                        <p class="hint">Tipo de telefone acima.</p></li>
                    <li><label class="required" for="tecnico_endereco_tipo">Tipo do endereço</label>

                        <select class="required endereco_tipo" id="tecnico_endereco_tipo" name="tecnico_endereco_tipo">
                            <option selected="selected" label="--" value="">--</option>
                            <option label="Comercial" value="comercial">Comercial</option>
                            <option label="Consultório" value="consultorio">Consultório</option>
                            <option label="Escritório" value="escritorio">Escritório</option>
                            <option label="Filial" value="filial">Filial</option>
                            <option label="Hotel" value="hotel">Hotel</option>
                            <option label="Matriz" value="matriz">Matriz</option>
                            <option label="Outro" value="outro">Outro</option>
                            <option label="Regularização" value="regularizacao">Regularização</option>
                            <option label="Residencial" value="residencial">Residencial</option>
                        </select></li>
                    <li><label class="required" for="tecnico_cep">CEP</label>

                        <input type="hidden" value="-22.546052,-48.635514" name="tecnico_cep_coord" id="tecnico_cep_coord"><input type="text" latlng="-22.546052,-48.635514" cep_url="http://republicavirtual.com.br/web_cep.php" maps_url="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=" sensor="false" default_zoom="6" zoom="23" title="CEP obrigatório." fil="#fieldset-DataTecnico " alt="cep" class="required cep" value="" id="tecnico_cep" name="tecnico_cep"></li>
                    <li><label class="required" for="tecnico_rua">Rua</label>

                        <input type="text" class="required endereco" value="" id="tecnico_rua" name="tecnico_rua"></li>
                    <li><label class="optional" for="tecnico_numero">Número</label>

                        <input type="text" class="numero" value="" id="tecnico_numero" name="tecnico_numero"></li>
                    <li><label class="optional" for="tecnico_complemento">Complemento</label>

                        <input type="text" class="complemento" value="" id="tecnico_complemento" name="tecnico_complemento"></li>
                    <li><label class="required" for="tecnico_bairro">Bairro</label>

                        <input type="text" class="required bairro" value="" id="tecnico_bairro" name="tecnico_bairro"></li>
                    <li><label class="required" for="tecnico_cidade">Cidade</label>

                        <input type="text" class="required cidade" value="" id="tecnico_cidade" name="tecnico_cidade"></li>
                    <li><label class="required" for="tecnico_estado">Estado</label>

                        <select class="required estado" id="tecnico_estado" name="tecnico_estado">
                            <option selected="selected" label="--" value="">--</option>
                            <option label="AC" value="AC">AC</option>
                            <option label="AL" value="AL">AL</option>
                            <option label="AM" value="AM">AM</option>
                            <option label="AP" value="AP">AP</option>
                            <option label="BA" value="BA">BA</option>
                            <option label="CE" value="CE">CE</option>
                            <option label="DF" value="DF">DF</option>
                            <option label="ES" value="ES">ES</option>
                            <option label="GO" value="GO">GO</option>
                            <option label="MA" value="MA">MA</option>
                            <option label="MG" value="MG">MG</option>
                            <option label="MS" value="MS">MS</option>
                            <option label="MT" value="MT">MT</option>
                            <option label="PA" value="PA">PA</option>
                            <option label="PB" value="PB">PB</option>
                            <option label="PE" value="pe">PE</option>
                            <option label="PI" value="pi">PI</option>
                            <option label="PR" value="PR">PR</option>
                            <option label="RJ" value="RJ">RJ</option>
                            <option label="RN" value="RN">RN</option>
                            <option label="RO" value="RO">RO</option>
                            <option label="RR" value="RR">RR</option>
                            <option label="RS" value="RS">RS</option>
                            <option label="SC" value="SC">SC</option>
                            <option label="SE" value="SE">SE</option>
                            <option label="SP" value="SP">SP</option>
                            <option label="TO" value="TO">TO</option>
                            <option label="Outros" value="outros">Outros</option>
                        </select></li>
                    <li><label class="optional" for="tecnico_ie">Inscrição Municipal</label>

                        <input type="text" class="ie" value="" id="tecnico_ie" name="tecnico_ie"></li></ul></fieldset></li>
        <li class="tab ui-corner-left" style="display: none;"><fieldset id="fieldset-DataCreaCalUser"><legend>5 - Área de Construção</legend>
                <ul>
                    <li>
                        <div class="messages errors" id="messages"></div></li>
                    <li><label class="optional" for="a_construir">À Construir</label>

                        <input type="text" role="integer" alt="integer" value="" id="a_construir" name="a_construir" style="text-align: right;"></li>
                    <li><label class="optional" for="a_regularizar">À Regularizar</label>

                        <input type="text" role="integer" alt="integer" value="" id="a_regularizar" name="a_regularizar" style="text-align: right;"></li>
                    <li><label class="optional" for="a_reformar">À Reformar</label>

                        <input type="text" role="integer" alt="integer" value="" id="a_reformar" name="a_reformar" style="text-align: right;"></li>
                    <li><label class="optional" for="ampliacao_a_construir">Ampliação à Construir</label>

                        <input type="text" role="integer" alt="integer" value="" id="ampliacao_a_construir" name="ampliacao_a_construir" style="text-align: right;"></li>
                    <li><label class="optional" for="ampliacao_a_regularizar">Ampliação à Regularizar</label>

                        <input type="text" role="integer" alt="integer" value="" id="ampliacao_a_regularizar" name="ampliacao_a_regularizar" style="text-align: right;"></li>
                    <li><label class="optional" for="a_transformar">À Transformar</label>

                        <input type="text" role="integer" alt="integer" value="" id="a_transformar" name="a_transformar" style="text-align: right;"></li>
                    <li><label class="optional" for="transformado">Transformado</label>

                        <input type="text" role="integer" alt="integer" value="" id="transformado" name="transformado" style="text-align: right;"></li>
                    <li><label class="optional" for="regularizada_com_habitese">Regularizada com Habite-se</label>

                        <input type="text" role="integer" alt="integer" value="" id="regularizada_com_habitese" name="regularizada_com_habitese" style="text-align: right;"></li>
                    <li><label class="optional" for="regularizada_com_certidao">Regularizada com Certidão</label>

                        <input type="text" role="integer" alt="integer" value="" id="regularizada_com_certidao" name="regularizada_com_certidao" style="text-align: right;"></li>
                    <li><label class="optional" for="a_demolir">À Demolir</label>

                        <input type="text" role="integer" alt="integer" value="" id="a_demolir" name="a_demolir" style="text-align: right;"></li>
                    <li><label class="optional" for="abrigo_desmontavel">Abrigo Desmontável</label>

                        <input type="text" role="integer" alt="integer" value="" id="abrigo_desmontavel" name="abrigo_desmontavel" style="text-align: right;"></li></ul></fieldset></li>
        <li class="tab ui-corner-left" style="display: none;"><fieldset id="fieldset-DataPagament"><legend>6 - Pagamento</legend>
                <ul>
                    <li><label class="required" for="confirmed">Pagamento</label>

                        <select class="required" id="confirmed" name="confirmed">
                            <option selected="selected" label="Não confirmado" value="0">Não confirmado</option>
                            <option label="Confirmado" value="1">Confirmado</option>
                        </select></li></ul></fieldset></li>
        <li class="button salvar ui-corner-left"><button class="" name="salvar" type="submit" id="salvar">Salvar</button></li></ul></form>