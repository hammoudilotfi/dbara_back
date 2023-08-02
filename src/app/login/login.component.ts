import { Component, OnInit } from '@angular/core';
import { Observable } from 'rxjs';
import { FormGroup, FormControl } from '@angular/forms';
import { ApiService } from 'src/services/api.service';
import { TokenService } from 'src/services/token.service';
import { Router } from '@angular/router';
import { HttpErrorResponse } from '@angular/common/http';
import { AnimationPlayer } from '@angular/animations';


@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {
  $login: any;
  token:any;
  error: string;

  credentialsForm = new FormGroup({
    username: new FormControl(''),
    password: new FormControl('')
  });
  constructor(
    private apiService: ApiService,
    private tokenService: TokenService,
    private router: Router
  ) { }
  ngOnInit() {
    this.token = this.tokenService.getAuthorizationToken();
  }
  onSubmit() {
    const credentials: { username: string; password: string } = {
      username: this.credentialsForm.value.username || '',
      password: this.credentialsForm.value.password || ''
    } 
    // Login should return jwt token
    this.apiService.postCredentials(credentials).subscribe(data=>{
      this.token = (data as any).token;
     this.tokenService.setAuthorizationToken(this.token) 
    console.log(data)
    return this.router.navigate(['user']);
    } 
    );
  }
  
  onLogout() {
    this.tokenService.cleanAuthorizationToken();
    this.token = null ;
    return this.router.navigate(['login']);
  }
}
