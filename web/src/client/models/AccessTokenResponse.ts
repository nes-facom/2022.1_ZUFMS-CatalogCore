/* tslint:disable */
/* eslint-disable */
/**
 * Coleção Zoológica Online ZUFMS
 * Coleção Zoológica – Incremento e movimentação do acervo  ## Visão geral 
 *
 * The version of the OpenAPI document: 1.0.0
 * Contact: suporte.agetic@ufms.br
 *
 * NOTE: This class is auto generated by OpenAPI Generator (https://openapi-generator.tech).
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */

import { exists, mapValues } from '../runtime';
/**
 * 
 * @export
 * @interface AccessTokenResponse
 */
export interface AccessTokenResponse {
    /**
     * 
     * @type {string}
     * @memberof AccessTokenResponse
     */
    accessToken?: string;
    /**
     * 
     * @type {string}
     * @memberof AccessTokenResponse
     */
    tokenType?: AccessTokenResponseTokenTypeEnum;
    /**
     * 
     * @type {number}
     * @memberof AccessTokenResponse
     */
    expiresIn?: number;
}

/**
* @export
* @enum {string}
*/
export enum AccessTokenResponseTokenTypeEnum {
    Bearer = 'bearer'
}

export function AccessTokenResponseFromJSON(json: any): AccessTokenResponse {
    return AccessTokenResponseFromJSONTyped(json, false);
}

export function AccessTokenResponseFromJSONTyped(json: any, ignoreDiscriminator: boolean): AccessTokenResponse {
    if ((json === undefined) || (json === null)) {
        return json;
    }
    return {
        
        'accessToken': !exists(json, 'access_token') ? undefined : json['access_token'],
        'tokenType': !exists(json, 'token_type') ? undefined : json['token_type'],
        'expiresIn': !exists(json, 'expires_in') ? undefined : json['expires_in'],
    };
}

export function AccessTokenResponseToJSON(value?: AccessTokenResponse | null): any {
    if (value === undefined) {
        return undefined;
    }
    if (value === null) {
        return null;
    }
    return {
        
        'access_token': value.accessToken,
        'token_type': value.tokenType,
        'expires_in': value.expiresIn,
    };
}

