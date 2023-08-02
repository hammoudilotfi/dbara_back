import { Component, OnInit, ViewChild, ElementRef, Renderer2 } from '@angular/core';
import { Router } from '@angular/router';
import { MatDialog } from '@angular/material/dialog';


@Component({
  selector: 'app-jeux',
  templateUrl: './jeux.component.html',
  styleUrls: ['./jeux.component.css']
})
export class JeuxComponent implements OnInit{
  
  
  constructor( private router: Router,
               private dialog: MatDialog
     ){}

  ngOnInit(){
}
openPopup(): void {
  this.dialog.open(JeuxComponent);
}
}



