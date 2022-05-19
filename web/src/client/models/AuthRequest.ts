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

import {
  ClientAuthRequest,
  ClientAuthRequestFromJSON,
  ClientAuthRequestFromJSONTyped,
  ClientAuthRequestToJSON,
} from "./ClientAuthRequest";
import {
  UserAuthRequest,
  UserAuthRequestFromJSON,
  UserAuthRequestFromJSONTyped,
  UserAuthRequestToJSON,
} from "./UserAuthRequest";

/**
 * @type AuthRequest
 *
 * @export
 */
export type AuthRequest = Record<string, string>;

export function AuthRequestFromJSON(json: any): AuthRequest {
  return AuthRequestFromJSONTyped(json, false);
}

export function AuthRequestFromJSONTyped(
  json: any,
  ignoreDiscriminator: boolean
): AuthRequest {
  if (json === undefined || json === null) {
    return json;
  }
  switch (json["type"]) {
    default:
      throw new Error(
        `No variant of AuthRequest exists with 'type=${json["type"]}'`
      );
  }
}

export function AuthRequestToJSON(value?: AuthRequest | null): any {
  if (value === undefined) {
    return undefined;
  }
  if (value === null) {
    return null;
  }
  switch (value["type"]) {
    default:
      throw new Error(
        `No variant of AuthRequest exists with 'type=${value["type"]}'`
      );
  }
}
