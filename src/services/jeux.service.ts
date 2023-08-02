import { HttpClient, HttpHeaders } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class JeuxService {
  private baseUrl = 'https://127.0.0.1:8000/api';
  constructor(private http :HttpClient) { }
  private getTokenFromLocalStorage(): string | null {
    return localStorage.getItem('access_token');
  }

  private getHeaders(): HttpHeaders {
    const token = this.getTokenFromLocalStorage();
    return new HttpHeaders().set('Authorization', `Bearer ${token}`);
  } 

  AjoutJeux(): Observable<any> {
    const headers = this.getHeaders();
    return this.http.get(`${this.baseUrl}/ajoutjeuxfront`, {headers});
  }
}
