import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-user',
  templateUrl: './user.component.html',
  styleUrls: ['./user.component.css']
})
export class UserComponent implements OnInit{
  name = "Lotfi";
  location ="tunisia"; 
  status ="activé";
constructor(){}
ngOnInit(): void {
}
   getStatus(){
       return this.status;
    }
    getMessage(){
      console.log("My new message");
    }
}
