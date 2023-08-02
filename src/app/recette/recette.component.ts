import { Token } from '@angular/compiler';
import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Recette } from 'src/models/recette';
import { RecetteService } from 'src/services/recette.service';
import { TokenService } from 'src/services/token.service';
import { HttpClient } from '@angular/common/http';


@Component({
  selector: 'app-recette',
  templateUrl: './recette.component.html',
  styleUrls: ['./recette.component.css']
})
export class RecetteComponent implements OnInit {
  recettes: Recette[];
  token : any;
  chef = {
    type: '',
    // Other form fields for 'nom', 'description', 'temps_preparation', 'nombre_ingredient', 'niv_difficulte', 'ingredients', 'apports_nutritifs', 'subcategory_id'
  };
  photoFile: File;
  videoFile: File;

  constructor(private recetteService :RecetteService,
            private route:Router,
            private tokenService: TokenService,
            private http: HttpClient){}
  ngOnInit(): void {
    //this.token = this.tokenService.getAuthorizationToken();
     this.getRecettes();
    console.log('token',this.token);
}

getRecettes(){
 // const token = 'your-token';
  this.recetteService.getData().subscribe(
    (recettes: Recette[]) => {
      this.recettes = recettes;
      console.log(recettes)
    },
    (error: any) => {
      console.error(error);
    }
  );
}


}
