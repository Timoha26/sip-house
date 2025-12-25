import {DEFAULT_CURRENCY_CODE, LOCALE_ID, NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';

import {AppRoutingModule} from './app-routing.module';
import {AppComponent} from './components/app.component';
import {HomeComponent} from './components/home/home.component';
import {NgbModule} from '@ng-bootstrap/ng-bootstrap';
import {ProjectsComponent} from "./components/projects/projects.component";
import {ProjectsService} from "./services/projects.service";
import {HttpClientModule} from "@angular/common/http";
import {registerLocaleData} from "@angular/common";
import localeRu from "@angular/common/locales/ru";
import {ProjectsDetailsComponent} from "./components/projects/projects.details.component";

registerLocaleData(localeRu)

@NgModule({
  declarations: [
    AppComponent,
    HomeComponent,
    ProjectsComponent,
    ProjectsDetailsComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    NgbModule,
    HttpClientModule
  ],
  providers: [
    {provide: LOCALE_ID, useValue: 'ru-RU'},
    {provide: DEFAULT_CURRENCY_CODE, useValue: 'RUB'},
    ProjectsService
  ],
  bootstrap: [AppComponent]
})
export class AppModule {
}
