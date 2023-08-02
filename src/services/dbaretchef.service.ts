import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { Dbaretchef } from 'src/models/dbaretchef';

@Injectable({
  providedIn: 'root'
})
export class DbaretchefService {

  private baseUrl = 'https://127.0.0.1:8000/api';
  constructor(private http :HttpClient) { }

  private getTokenFromLocalStorage(): string | null {
    return localStorage.getItem('access_token');
  }

  private getHeaders(): HttpHeaders {
    const token = this.getTokenFromLocalStorage();
    return new HttpHeaders().set('Authorization', `Bearer ${token}`);
  } 

  getData(): Observable<any> {
    const headers = this.getHeaders();
    return this.http.get(`${this.baseUrl}/getdbaretchef`, {headers});
  }

  public getDbaretchefs(token :string): Observable<any>{
    const headers = this.getHeaders();
    return this.http.get<any>(`${this.baseUrl}/showalldbaretchef`, {headers});
  }

  getDbaretchef(id: number): Observable<any> {
    const headers = this.getHeaders();
    return this.http.get<any>(`${this.baseUrl}/showdbaretchef/${id}`, {headers});
  }

  createDbaretchef(dbaretchef: Dbaretchef, token: string): Observable<Dbaretchef> {
    const headers = new HttpHeaders().set('Authorization', `Bearer ${token}`);
    return this.http.post<Dbaretchef>(`${this.baseUrl}/ajoutdbaretchef`, dbaretchef, { headers });
    //return this.http.post<any>(`${this.baseUrl}/addrecette`, recette);
  }

  updateDbaretchef(id: number, dbaretchef: any): Observable<any> {
    return this.http.put<any>(`${this.baseUrl}/editdbaretchef/${id}`, dbaretchef);
  }
  searchDbaretchef(nom: string): Observable<any> {
    const headers = this.getHeaders();
    return this.http.get(`${this.baseUrl}/searchdbaretchef/${nom}`, { headers });
  }
}
