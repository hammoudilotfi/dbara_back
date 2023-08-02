import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Dbaretchef } from 'src/models/dbaretchef';
import { DbaretchefService } from 'src/services/dbaretchef.service';
import { TokenService } from 'src/services/token.service';

@Component({
  selector: 'app-dbaretchef',
  templateUrl: './dbaretchef.component.html',
  styleUrls: ['./dbaretchef.component.css']
})
export class DbaretchefComponent implements OnInit {
  token = 'your-token';
  data: any;
  dbaretchef: any;
  searchText: string;
  searchId: string;
  recettes: any[];
  constructor(private dbaretchefservice : DbaretchefService,
          private route:Router,
          private tokenService: TokenService){}

  ngOnInit(): void {
    this.token = this.tokenService.getAuthorizationToken();
    this.getDbaretchefs();
}
getDbaretchefs(){
   this.dbaretchefservice.getData().subscribe(
    response => {
      this.data = response;
      console.log('response',this.data);
    },
    error => {
      console.error(error);
    }
   );
 }
 createDbaretchef() {
  this.dbaretchefservice.createDbaretchef(this.dbaretchef,this.token).subscribe(
    (response) => {
      console.log('Data saved successfully:', response);
      // Handle success, if required
    },
    (error) => {
      console.error('Error saving data:', error);
      // Handle error, if required
    }
  );
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
  uploadImage(event: any): void {
    const file = event.target.files[0];
    // Perform further operations with the file, such as sending it to the server
  }
}
