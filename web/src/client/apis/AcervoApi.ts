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


import * as runtime from '../runtime';
import {
    ErrorResponse,
    ErrorResponseFromJSON,
    ErrorResponseToJSON,
    InlineResponse200,
    InlineResponse200FromJSON,
    InlineResponse200ToJSON,
    InsufficientPermissionsErrorResponse,
    InsufficientPermissionsErrorResponseFromJSON,
    InsufficientPermissionsErrorResponseToJSON,
    User,
    UserFromJSON,
    UserToJSON,
    ZUFMSCore,
    ZUFMSCoreFromJSON,
    ZUFMSCoreToJSON,
} from '../models';

export interface CreateAcervoEntriesRequest {
    zUFMSCore?: Array<ZUFMSCore>;
}

export interface DeleteAcervoEntriesRequest {
    requestBody?: Array<string>;
}

export interface DeleteAcervoEntryRequest {
    occurrenceID: string;
}

export interface GetAcervoEntryRequest {
    occurrenceID: string;
}

export interface UpdateAcervoEntriesRequest {
    zUFMSCore?: Array<ZUFMSCore>;
}

export interface UpdateAcervoEntryRequest {
    occurrenceID: string;
    zUFMSCore?: ZUFMSCore;
}

/**
 * 
 */
export class AcervoApi extends runtime.BaseAPI {

    /**
     * Adicionar múltiplas entradas ao acervo
     */
    async createAcervoEntriesRaw(requestParameters: CreateAcervoEntriesRequest, initOverrides?: RequestInit): Promise<runtime.ApiResponse<User>> {
        const queryParameters: any = {};

        const headerParameters: runtime.HTTPHeaders = {};

        headerParameters['Content-Type'] = 'application/json';

        if (this.configuration && this.configuration.accessToken) {
            const token = this.configuration.accessToken;
            const tokenString = await token("access_token", []);

            if (tokenString) {
                headerParameters["Authorization"] = `Bearer ${tokenString}`;
            }
        }
        const response = await this.request({
            path: `/acervo`,
            method: 'POST',
            headers: headerParameters,
            query: queryParameters,
            body: requestParameters.zUFMSCore.map(ZUFMSCoreToJSON),
        }, initOverrides);

        return new runtime.JSONApiResponse(response, (jsonValue) => UserFromJSON(jsonValue));
    }

    /**
     * Adicionar múltiplas entradas ao acervo
     */
    async createAcervoEntries(requestParameters: CreateAcervoEntriesRequest = {}, initOverrides?: RequestInit): Promise<User> {
        const response = await this.createAcervoEntriesRaw(requestParameters, initOverrides);
        return await response.value();
    }

    /**
     * Excluir múltiplas entradas do acervo
     */
    async deleteAcervoEntriesRaw(requestParameters: DeleteAcervoEntriesRequest, initOverrides?: RequestInit): Promise<runtime.ApiResponse<Array<ZUFMSCore>>> {
        const queryParameters: any = {};

        const headerParameters: runtime.HTTPHeaders = {};

        headerParameters['Content-Type'] = 'application/json';

        if (this.configuration && this.configuration.accessToken) {
            const token = this.configuration.accessToken;
            const tokenString = await token("access_token", []);

            if (tokenString) {
                headerParameters["Authorization"] = `Bearer ${tokenString}`;
            }
        }
        const response = await this.request({
            path: `/acervo`,
            method: 'DELETE',
            headers: headerParameters,
            query: queryParameters,
            body: requestParameters.requestBody,
        }, initOverrides);

        return new runtime.JSONApiResponse(response, (jsonValue) => jsonValue.map(ZUFMSCoreFromJSON));
    }

    /**
     * Excluir múltiplas entradas do acervo
     */
    async deleteAcervoEntries(requestParameters: DeleteAcervoEntriesRequest = {}, initOverrides?: RequestInit): Promise<Array<ZUFMSCore>> {
        const response = await this.deleteAcervoEntriesRaw(requestParameters, initOverrides);
        return await response.value();
    }

    /**
     * Excluir uma entrada do acervo
     */
    async deleteAcervoEntryRaw(requestParameters: DeleteAcervoEntryRequest, initOverrides?: RequestInit): Promise<runtime.ApiResponse<User>> {
        if (requestParameters.occurrenceID === null || requestParameters.occurrenceID === undefined) {
            throw new runtime.RequiredError('occurrenceID','Required parameter requestParameters.occurrenceID was null or undefined when calling deleteAcervoEntry.');
        }

        const queryParameters: any = {};

        const headerParameters: runtime.HTTPHeaders = {};

        if (this.configuration && this.configuration.accessToken) {
            const token = this.configuration.accessToken;
            const tokenString = await token("access_token", []);

            if (tokenString) {
                headerParameters["Authorization"] = `Bearer ${tokenString}`;
            }
        }
        const response = await this.request({
            path: `/acervo/{occurrenceID}`.replace(`{${"occurrenceID"}}`, encodeURIComponent(String(requestParameters.occurrenceID))),
            method: 'DELETE',
            headers: headerParameters,
            query: queryParameters,
        }, initOverrides);

        return new runtime.JSONApiResponse(response, (jsonValue) => UserFromJSON(jsonValue));
    }

    /**
     * Excluir uma entrada do acervo
     */
    async deleteAcervoEntry(requestParameters: DeleteAcervoEntryRequest, initOverrides?: RequestInit): Promise<User> {
        const response = await this.deleteAcervoEntryRaw(requestParameters, initOverrides);
        return await response.value();
    }

    /**
     * Buscar entradas do acervo
     */
    async getAcervoEntriesRaw(initOverrides?: RequestInit): Promise<runtime.ApiResponse<Array<ZUFMSCore>>> {
        const queryParameters: any = {};

        const headerParameters: runtime.HTTPHeaders = {};

        if (this.configuration && this.configuration.accessToken) {
            const token = this.configuration.accessToken;
            const tokenString = await token("access_token", []);

            if (tokenString) {
                headerParameters["Authorization"] = `Bearer ${tokenString}`;
            }
        }
        const response = await this.request({
            path: `/acervo`,
            method: 'GET',
            headers: headerParameters,
            query: queryParameters,
        }, initOverrides);

        return new runtime.JSONApiResponse(response, (jsonValue) => jsonValue.map(ZUFMSCoreFromJSON));
    }

    /**
     * Buscar entradas do acervo
     */
    async getAcervoEntries(initOverrides?: RequestInit): Promise<Array<ZUFMSCore>> {
        const response = await this.getAcervoEntriesRaw(initOverrides);
        return await response.value();
    }

    /**
     * Buscar uma entrada do acervo
     */
    async getAcervoEntryRaw(requestParameters: GetAcervoEntryRequest, initOverrides?: RequestInit): Promise<runtime.ApiResponse<User>> {
        if (requestParameters.occurrenceID === null || requestParameters.occurrenceID === undefined) {
            throw new runtime.RequiredError('occurrenceID','Required parameter requestParameters.occurrenceID was null or undefined when calling getAcervoEntry.');
        }

        const queryParameters: any = {};

        const headerParameters: runtime.HTTPHeaders = {};

        if (this.configuration && this.configuration.accessToken) {
            const token = this.configuration.accessToken;
            const tokenString = await token("access_token", []);

            if (tokenString) {
                headerParameters["Authorization"] = `Bearer ${tokenString}`;
            }
        }
        const response = await this.request({
            path: `/acervo/{occurrenceID}`.replace(`{${"occurrenceID"}}`, encodeURIComponent(String(requestParameters.occurrenceID))),
            method: 'GET',
            headers: headerParameters,
            query: queryParameters,
        }, initOverrides);

        return new runtime.JSONApiResponse(response, (jsonValue) => UserFromJSON(jsonValue));
    }

    /**
     * Buscar uma entrada do acervo
     */
    async getAcervoEntry(requestParameters: GetAcervoEntryRequest, initOverrides?: RequestInit): Promise<User> {
        const response = await this.getAcervoEntryRaw(requestParameters, initOverrides);
        return await response.value();
    }

    /**
     * Realizar o upload e processamento de uma ou mais entradas formatadas em xlsx ou csv
     * Realizar o upload e processamento de um arquivo de entradas
     */
    async processAcervoEntriesFileRaw(initOverrides?: RequestInit): Promise<runtime.ApiResponse<InlineResponse200>> {
        const queryParameters: any = {};

        const headerParameters: runtime.HTTPHeaders = {};

        if (this.configuration && this.configuration.accessToken) {
            const token = this.configuration.accessToken;
            const tokenString = await token("access_token", []);

            if (tokenString) {
                headerParameters["Authorization"] = `Bearer ${tokenString}`;
            }
        }
        const response = await this.request({
            path: `/acervo/file`,
            method: 'POST',
            headers: headerParameters,
            query: queryParameters,
        }, initOverrides);

        return new runtime.JSONApiResponse(response, (jsonValue) => InlineResponse200FromJSON(jsonValue));
    }

    /**
     * Realizar o upload e processamento de uma ou mais entradas formatadas em xlsx ou csv
     * Realizar o upload e processamento de um arquivo de entradas
     */
    async processAcervoEntriesFile(initOverrides?: RequestInit): Promise<InlineResponse200> {
        const response = await this.processAcervoEntriesFileRaw(initOverrides);
        return await response.value();
    }

    /**
     * Editar múltiplas entradas do acervo
     */
    async updateAcervoEntriesRaw(requestParameters: UpdateAcervoEntriesRequest, initOverrides?: RequestInit): Promise<runtime.ApiResponse<User>> {
        const queryParameters: any = {};

        const headerParameters: runtime.HTTPHeaders = {};

        headerParameters['Content-Type'] = 'application/json';

        if (this.configuration && this.configuration.accessToken) {
            const token = this.configuration.accessToken;
            const tokenString = await token("access_token", []);

            if (tokenString) {
                headerParameters["Authorization"] = `Bearer ${tokenString}`;
            }
        }
        const response = await this.request({
            path: `/acervo`,
            method: 'PUT',
            headers: headerParameters,
            query: queryParameters,
            body: requestParameters.zUFMSCore.map(ZUFMSCoreToJSON),
        }, initOverrides);

        return new runtime.JSONApiResponse(response, (jsonValue) => UserFromJSON(jsonValue));
    }

    /**
     * Editar múltiplas entradas do acervo
     */
    async updateAcervoEntries(requestParameters: UpdateAcervoEntriesRequest = {}, initOverrides?: RequestInit): Promise<User> {
        const response = await this.updateAcervoEntriesRaw(requestParameters, initOverrides);
        return await response.value();
    }

    /**
     * Editar uma entrada do acervo
     */
    async updateAcervoEntryRaw(requestParameters: UpdateAcervoEntryRequest, initOverrides?: RequestInit): Promise<runtime.ApiResponse<User>> {
        if (requestParameters.occurrenceID === null || requestParameters.occurrenceID === undefined) {
            throw new runtime.RequiredError('occurrenceID','Required parameter requestParameters.occurrenceID was null or undefined when calling updateAcervoEntry.');
        }

        const queryParameters: any = {};

        const headerParameters: runtime.HTTPHeaders = {};

        headerParameters['Content-Type'] = 'application/json';

        if (this.configuration && this.configuration.accessToken) {
            const token = this.configuration.accessToken;
            const tokenString = await token("access_token", []);

            if (tokenString) {
                headerParameters["Authorization"] = `Bearer ${tokenString}`;
            }
        }
        const response = await this.request({
            path: `/acervo/{occurrenceID}`.replace(`{${"occurrenceID"}}`, encodeURIComponent(String(requestParameters.occurrenceID))),
            method: 'PUT',
            headers: headerParameters,
            query: queryParameters,
            body: ZUFMSCoreToJSON(requestParameters.zUFMSCore),
        }, initOverrides);

        return new runtime.JSONApiResponse(response, (jsonValue) => UserFromJSON(jsonValue));
    }

    /**
     * Editar uma entrada do acervo
     */
    async updateAcervoEntry(requestParameters: UpdateAcervoEntryRequest, initOverrides?: RequestInit): Promise<User> {
        const response = await this.updateAcervoEntryRaw(requestParameters, initOverrides);
        return await response.value();
    }

}