import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { UserComponent } from './user/user.component';
import { RecetteComponent } from './recette/recette.component';

import { LoginComponent } from './login/login.component';
import { AuthGuard } from './auth.guard';
import { DbaretchefComponent } from './dbaretchef/dbaretchef.component';
import { JeuxComponent } from './jeux/jeux.component';


const routes: Routes = [
  { path: 'login', component: LoginComponent },
  {path : "DbaretChef",component :DbaretchefComponent},
  {path : "user",canActivate: [AuthGuard], data: { credentials: { username: 'your-username', password: 'your-password' }} ,component :UserComponent},
  {path : "recette",canActivate: [AuthGuard],component :RecetteComponent},
  {path : "recette",component :RecetteComponent},
  {path : "jeux",component :JeuxComponent},
  {path : "**",component :UserComponent},
  
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
