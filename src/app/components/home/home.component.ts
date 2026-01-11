import {Component} from "@angular/core";
import {ProjectsService} from "../../services/projects.service";
import {ProjectModel} from "../../models/project.model";
import {ImageModel} from "../../models/image.model";
import {RequestModel} from "../../models/request.model";
import {ObjectTypesEnum} from "../../models/objectTypes.enum";
import {createMask} from "@ngneat/input-mask";
import {ObjectTypeModel} from "../../models/objectType.model";

@Component({
  templateUrl: 'home.component.html'
})
export class HomeComponent {
  phoneMask = createMask({ mask: '+7 (999) 999-99-99', keepStatic: true });
  emailMask = createMask({ alias: 'email' });

  projects: ProjectModel[] = [];

  request: RequestModel = {
    name: undefined,
    phone: undefined,
    email: undefined,
    comment: undefined,
    objectType: ObjectTypesEnum.ResidentialHouse
  };

  objectTypes: ObjectTypeModel[] = [
    {key: ObjectTypesEnum.ResidentialHouse, value: 'Дом для постоянного проживания'},
    {key: ObjectTypesEnum.CountryHouse, value: 'Дачный дом'},
    {key: ObjectTypesEnum.GuestHouse, value: 'Гостевой дом'},
    {key: ObjectTypesEnum.CommercialObject, value: 'Коммерческий обьект'}
  ];

  constructor(private projectsService: ProjectsService) {
  }

  private getProjects() {
    this.projectsService.get().subscribe({next: data => this.projects = data});
  }

  getMainImage(images: ImageModel[]): string {
    for (let i = 0; i < images.length; i++)
      if (images[i].mainImage)
        return images[i].url;

    return '';
  }

  sentRequest() {
    console.log(this.request);
  }

  ngOnInit() {
    this.getProjects();
  }
}
