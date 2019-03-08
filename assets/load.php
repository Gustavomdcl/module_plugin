<?php

add_action('wp_enqueue_scripts','add_assets_header');
add_action('admin_head','add_assets_header');
function add_assets_header() { ?>
    <script src="https://code.jquery.com/jquery-1.11.3.js"></script>
    <!-- mask https://igorescobar.github.io/jQuery-Mask-Plugin/ -->
    <script src="<?php echo plugins_url('/js/jquery.mask.min.js',__FILE__); ?>"></script>
    <!-- validate https://jqueryvalidation.org/validate/ -->
    <script src="<?php echo plugins_url('/js/jquery.validate.min.js',__FILE__); ?>"></script>
    <script type="text/javascript">
        //Traduz campos
            jQuery.extend(jQuery.validator.messages, {
                required: "*O campo é obrigatório",
                remote: "Ajuste o campo",
                email: "Insira um email válido.",
                url: "Insira um link válido",
                date: "Insira uma data válida",
                dateISO: "Insira uma data válida (ISO).",
                number: "Insira um número válido.",
                digits: "Insira apenas digitos.",
                creditcard: "Insira um cartão válido",
                equalTo: "Insira o mesmo valor novamente",
                accept: "Insira um formato válido",
                maxlength: jQuery.validator.format("Insira no máximo {0} caracteres."),
                minlength: jQuery.validator.format("Insira no mínimo {0} caracteres."),
                rangelength: jQuery.validator.format("Insira entre {0} e {1} caracteres."),
                range: jQuery.validator.format("Insira um valor entre {0} e {1}."),
                max: jQuery.validator.format("Insira um valor menor ou igual a {0}."),
                min: jQuery.validator.format("Insira um valor maior ou igual a {0}.")
            });
        //Mask - Cria máscara para São Paulo
            var phone_mask = function (val) {
                return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
            },
            spOptions = {
                onKeyPress: function(val, e, field, options) {
                    field.mask(phone_mask.apply({}, arguments), options);
                }
            };
        //Space for Username
            jQuery.validator.addMethod("nospace", function(value, element) { 
                return value.indexOf(" ") < 0 && value != ""; 
            }, "Não é permitido espaço");
        //Validar CPF
            //https://pt.stackoverflow.com/questions/44061/usando-jquery-validation-engine-e-valida%C3%A7%C3%A3o-de-cpf
            jQuery.validator.addMethod("cpf", function(value, element) {
                value = jQuery.trim(value);

                value = value.replace('.','');
                value = value.replace('.','');
                cpf = value.replace('-','');
                while(cpf.length < 11) cpf = "0"+ cpf;
                var expReg = /^0+$|^1+$|^2+$|^3+$|^4+$|^5+$|^6+$|^7+$|^8+$|^9+$/;
                var a = [];
                var b = new Number;
                var c = 11;
                for (i=0; i<11; i++){
                    a[i] = cpf.charAt(i);
                    if (i < 9) b += (a[i] * --c);
                }
                if ((x = b % 11) < 2) { a[9] = 0 } else { a[9] = 11-x }
                b = 0;
                c = 11;
                for (y=0; y<10; y++) b += (a[y] * c--);
                if ((x = b % 11) < 2) { a[10] = 0; } else { a[10] = 11-x; }

                var retorno = true;
                if ((cpf.charAt(9) != a[9]) || (cpf.charAt(10) != a[10]) || cpf.match(expReg)) retorno = false;

                return this.optional(element) || retorno;

            }, "Informe um CPF válido");
        //Validar CNPJ
            //https://pt.stackoverflow.com/questions/7214/usando-jquery-validation-engine-e-valida%C3%A7%C3%A3o-de-cnpj
            jQuery.validator.addMethod("cnpj", function(value, element) {
                value = jQuery.trim(value);
                cnpj = value.replace(/[^\d]+/g, '');
                if (cnpj == '') return false;
                if (cnpj.length != 14)
                    return false;
                // Elimina CNPJs invalidos conhecidos
                if (cnpj == "00000000000000" ||
                    cnpj == "11111111111111" ||
                    cnpj == "22222222222222" ||
                    cnpj == "33333333333333" ||
                    cnpj == "44444444444444" ||
                    cnpj == "55555555555555" ||
                    cnpj == "66666666666666" ||
                    cnpj == "77777777777777" ||
                    cnpj == "88888888888888" ||
                    cnpj == "99999999999999")
                    return false;

                // Valida DVs
                tamanho = cnpj.length - 2
                numeros = cnpj.substring(0, tamanho);
                digitos = cnpj.substring(tamanho);
                soma = 0;
                pos = tamanho - 7;
                for (i = tamanho; i >= 1; i--) {
                    soma += numeros.charAt(tamanho - i) * pos--;
                    if (pos < 2)
                        pos = 9;
                }
                resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
                if (resultado != digitos.charAt(0))
                    return false;

                tamanho = tamanho + 1;
                numeros = cnpj.substring(0, tamanho);
                soma = 0;
                pos = tamanho - 7;
                for (i = tamanho; i >= 1; i--) {
                    soma += numeros.charAt(tamanho - i) * pos--;
                    if (pos < 2)
                        pos = 9;
                }
                resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
                if (resultado != digitos.charAt(1))
                    return false;

                return true;

            }, "Informe um CNPJ válido");
        //File Size
            jQuery.validator.addMethod('filesize', function (value, element, param) {
                return this.optional(element) || (element.files[0].size <= param)
            }, 'O arquivo deve ser menor que {0} bytes');
        //CONTA CARACTERES ======
            function charactersCount(element,e){
                var thisTextarea = jQuery(element);
                var text_max = thisTextarea.attr('maxlength');
                if(text_max!=undefined){
                    thisTextarea.after('<p>Tamanho máximo da resposta: '+text_max+' caracteres<br><span class="textarea_feedback-'+e+'">Faltam '+text_max+'</span></p>');
                    thisTextarea.keyup(function() {
                        var text_length = thisTextarea.val().length;
                        var text_remaining = text_max - text_length;
                        if(text_length>=text_max){
                            text_remaining = 0;
                        }
                        jQuery('.textarea_feedback-'+e).html('Faltam '+text_remaining);
                    });
                }
            }
    </script>
<?php }