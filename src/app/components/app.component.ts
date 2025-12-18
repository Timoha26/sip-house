import { Component } from '@angular/core';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.sass']
})
export class AppComponent {
  title = 'СИПдом';

  fullYear: number = 0;

  ngOnInit() {
    this.fullYear = new Date().getFullYear();
  }
}
