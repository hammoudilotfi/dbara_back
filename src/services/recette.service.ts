import { HttpClient ,HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { HttpResponse } from '@angular/common/http';
import { Recette } from 'src/models/recette';

@Injectable({
  providedIn: 'root'
})
export class RecetteService {

  private baseUrl = 'https://127.0.0.1:8000/api';
  
  constructor(private http :HttpClient) { }
  
  private getTokenFromLocalStorage(): string | null {
    return localStorage.getItem('access_token');
  }

  private getHeaders(): HttpHeaders {
    const token = this.getTokenFromLocalStorage();
    return new HttpHeaders().set('Authorization', `Bearer ${token}`);
  } 
  
  /*getData(): Observable<any> {
    const headers = this.getHeaders();
    return this.http.get(`${this.baseUrl}/getrecette`, { headers });
  }*/

  getData(): Observable<any> {
    const headers = this.getHeaders();
    return this.http.get(`${this.baseUrl}/getrecette`, {headers});
  }
  public getRecettes(token: string): Observable<Recette[]> {
   const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);
   //const headers = new HttpHeaders({'Content-Type': 'application/json;', 'Authorization':  `Bearer ${token}`});
    const authtoken = localStorage.getItem('access_token');
   
    return this.http.get<Recette[]>(`${this.baseUrl}/getrecette`, {headers});
  }

  getRecette(id: number): Observable<any> {
    return this.http.get<any>(`${this.baseUrl}/showrecette/${id}`);
  }

  createRecette(recette: Recette, token: string): Observable<Recette> {
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);
    return this.http.post<Recette>(`${this.baseUrl}/addrecette`, recette, { headers });
    //return this.http.post<any>(`${this.baseUrl}/addrecette`, recette);
  }

  updateRecette(id: number, recette: any): Observable<any> {
    return this.http.put<any>(`${this.baseUrl}/editrecette/${id}`, recette);
  }

  deleteRecette(id: number): Observable<any> {
    return this.http.delete<any>(`${this.baseUrl}/deleterecette/${id}`);
  }
}
