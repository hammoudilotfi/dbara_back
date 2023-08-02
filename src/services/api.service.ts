import { Injectable } from '@angular/core';
import { HttpClient , HttpHeaders} from '@angular/common/http';
import { Observable } from 'rxjs';


interface CredentialsType {
  username: string;
  password: string;
}
const headers = new HttpHeaders({
  'Content-Type': 'application/json'
});
 const httpOptions = {
  headers: new HttpHeaders({ 'Content-Type': 'application/json' }) 
};
@Injectable({
  providedIn: 'root'
})
export class ApiService {
  public credentiel :CredentialsType;
  private apiUrl = '/api/login_check';
  constructor(private http: HttpClient) { }
  private getRequestHeaders(): HttpHeaders {
    return new HttpHeaders({
      'Content-Type': 'application/json',
     
    });
  }
  postCredentials(
    credentials: CredentialsType

  ) {
    const headers = this.getRequestHeaders();
    return this.http.post('https://127.0.0.1:8000/api/login_check', credentials,{headers});
  }
  login(credential: CredentialsType): Observable<any> {
    const url = `${this.apiUrl}/login_check`;
    const headers = this.getAuthHeaders();

    return this.http.post(url, credential, { headers });
  }
  getAuthHeaders(): HttpHeaders {
    const token = this.getToken();

    if (token) {
      return new HttpHeaders({
        Authorization: `Bearer ${token}`
      });
    }

    return new HttpHeaders();
  }
  saveToken(token: string): void {
    localStorage.setItem('token', token);
  }
  getToken(): string | null {
    return localStorage.getItem('token');
  }
}
