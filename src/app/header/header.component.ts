import { Component, OnInit, ViewChild, ElementRef, Renderer2 } from '@angular/core';
import { Router } from '@angular/router';
import { Dbaretchef } from 'src/models/dbaretchef';
import { ApiService } from 'src/services/api.service';
import { DbaretchefService } from 'src/services/dbaretchef.service';
import { TokenService } from 'src/services/token.service';


@Component({
  selector: 'app-header',
  templateUrl: './header.component.html',
  styleUrls: ['./header.component.css']
})
export class HeaderComponent implements OnInit {
  token:any;
  dataSource:Dbaretchef;
  @ViewChild('myModal') myModal!: ElementRef;

  searchText: string;
  searchId: string;
  recettes: any[];
  constructor(
    private dbaretchefservice : DbaretchefService,
    private apiService: ApiService,
    private tokenService: TokenService,
    private router: Router,
    private renderer: Renderer2
  ) {}
  ngOnInit() : void{
    this.token = this.tokenService.getAuthorizationToken();
  }

  onLogout() {
    this.tokenService.cleanAuthorizationToken();
    this.token = null ;
    return this.router.navigate(['login']);
  }
  public doFilter = (value: string) => {
   
    this.dataSource.nom = value.trim().toLocaleLowerCase();

  }
  openPopup(): void {
    this.renderer.addClass(this.myModal.nativeElement, 'show');
    this.renderer.setStyle(document.body, 'overflow', 'hidden');
  }

  closePopup(): void {
    this.renderer.removeClass(this.myModal.nativeElement, 'show');
    this.renderer.removeStyle(document.body, 'overflow');
  }
  searchByName() {
    // Pass the searchId value as an argument to the method
    this.dbaretchefservice.searchDbaretchef(this.searchText).subscribe(
      (response: any) => {
        this.recettes = response; 
        console.log(this.recettes);
      },
      (error) => {
        // Handle the error here
        console.error(error);
      }
    );
  }
}
