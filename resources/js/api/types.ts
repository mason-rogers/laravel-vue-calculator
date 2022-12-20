export interface CalculateRequest {
    expression: string;
}

export interface CalculateResponse {
    success: boolean;
    error?: string;
    result: number;
}

export interface ICalculation {
    id: number;
    expression: string;
    result: number;
    created_at: string;
}

export interface CalculationsResponse {
    data: ICalculation[];
}
