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
 * @interface ClientAuthRequest
 */
export interface ClientAuthRequest {
    /**
     * 
     * @type {string}
     * @memberof ClientAuthRequest
     */
    type?: ClientAuthRequestTypeEnum;
    /**
     * 
     * @type {string}
     * @memberof ClientAuthRequest
     */
    clientId?: string;
    /**
     * 
     * @type {string}
     * @memberof ClientAuthRequest
     */
    clientSecret?: string;
}

/**
* @export
* @enum {string}
*/
export enum ClientAuthRequestTypeEnum {
    ClientCredentials = 'client_credentials'
}

export function ClientAuthRequestFromJSON(json: any): ClientAuthRequest {
    return ClientAuthRequestFromJSONTyped(json, false);
}

export function ClientAuthRequestFromJSONTyped(json: any, ignoreDiscriminator: boolean): ClientAuthRequest {
    if ((json === undefined) || (json === null)) {
        return json;
    }
    return {
        
        'type': !exists(json, 'type') ? undefined : json['type'],
        'clientId': !exists(json, 'client_id') ? undefined : json['client_id'],
        'clientSecret': !exists(json, 'client_secret') ? undefined : json['client_secret'],
    };
}

export function ClientAuthRequestToJSON(value?: ClientAuthRequest | null): any {
    if (value === undefined) {
        return undefined;
    }
    if (value === null) {
        return null;
    }
    return {
        
        'type': value.type,
        'client_id': value.clientId,
        'client_secret': value.clientSecret,
    };
}
