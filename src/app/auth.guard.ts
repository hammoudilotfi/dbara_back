import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, CanActivate, Router, RouterStateSnapshot } from '@angular/router';
import { ApiService } from 'src/services/api.service';

@Injectable({
  providedIn: 'root'
})
export class AuthGuard implements CanActivate {

  constructor(private router: Router,
             private auth :ApiService) {}
    async canActivate(): Promise<boolean> {   
      try {
        await this.auth.login(this.auth.credentiel).subscribe;
        return true; // User is authenticated, allow access
      }catch (error) {
        this.router.navigate(['/login']); // User is not authenticated, redirect to login page
        console.log(error);
        return false;
      }
  }

}