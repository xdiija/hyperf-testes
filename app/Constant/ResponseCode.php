<?php

namespace App\Constant;

class ResponseCode
{
    /** * Sucesso - Requisição bem-sucedida */
    public const OK = 200;
    /** * Criado - Recurso criado com sucesso */
    public const CREATED = 201;
    /** * Solicitação Inválida - A requisição contém erros de sintaxe ou parâmetros inválidos */
    public const BAD_REQUEST = 400;
    /** * Não Autorizado - A requisição não foi autorizada */
    public const UNAUTHORIZED = 401;
    /** * Proibido - O servidor entendeu a requisição, mas se recusa a autorizá-la */
    public const FORBIDDEN = 403;
    /** * Não Encontrado - O recurso solicitado não foi encontrado no servidor */
    public const NOT_FOUND = 404;
    /** * Conflito - Conflito de dados, como duplicação de registros */
    public const CONFLICT = 409;
    /** * Entidade Não Processável - A requisição foi bem formada, mas não pode ser processada */
    public const UNPROCESSABLE_ENTITY = 422;
    /** * Erro Interno do Servidor - O servidor encontrou um erro ao processar a requisição */
    public const SERVER_ERROR = 500;
}
