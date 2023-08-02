import { Component } from '@angular/core';

@Component({
  selector: 'app-search',
  templateUrl: './search.component.html',
  styleUrls: ['./search.component.css']
})
export class SearchComponent {
  items: any[] = [
    { name: 'Item 1', description: 'Description of Item 1' },
    { name: 'Item 2', description: 'Description of Item 2' },
    { name: 'Item 3', description: 'Description of Item 3' },
    // Add more items as needed
  ];

  searchQuery: string = '';
  filteredItems: any[] = [];

constructor(){
  this.filteredItems = this.items;
}
search(): void {
    this.filteredItems = this.items.filter((item) =>
      item.name.toLowerCase().includes(this.searchQuery.toLowerCase())
    );
  }
}
