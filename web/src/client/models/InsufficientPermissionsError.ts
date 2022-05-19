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
import {
    ModelError,
    ModelErrorFromJSON,
    ModelErrorFromJSONTyped,
    ModelErrorToJSON,
} from './ModelError';

/**
 * 
 * @export
 * @interface InsufficientPermissionsError
 */
export interface InsufficientPermissionsError {
    /**
     * 
     * @type {number}
     * @memberof InsufficientPermissionsError
     */
    code?: InsufficientPermissionsErrorCodeEnum;
    /**
     * 
     * @type {string}
     * @memberof InsufficientPermissionsError
     */
    title?: InsufficientPermissionsErrorTitleEnum;
    /**
     * 
     * @type {string}
     * @memberof InsufficientPermissionsError
     */
    description?: InsufficientPermissionsErrorDescriptionEnum;
}

export function InsufficientPermissionsErrorFromJSON(json: any): InsufficientPermissionsError {
    return InsufficientPermissionsErrorFromJSONTyped(json, false);
}

export function InsufficientPermissionsErrorFromJSONTyped(json: any, ignoreDiscriminator: boolean): InsufficientPermissionsError {
    if ((json === undefined) || (json === null)) {
        return json;
    }
    return {
        
        'code': !exists(json, 'code') ? undefined : json['code'],
        'title': !exists(json, 'title') ? undefined : json['title'],
        'description': !exists(json, 'description') ? undefined : json['description'],
    };
}

export function InsufficientPermissionsErrorToJSON(value?: InsufficientPermissionsError | null): any {
    if (value === undefined) {
        return undefined;
    }
    if (value === null) {
        return null;
    }
    return {
        
        'code': value.code,
        'title': value.title,
        'description': value.description,
    };
}

