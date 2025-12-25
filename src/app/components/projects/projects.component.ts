import {Component} from "@angular/core";
import {ProjectModel} from "../../models/project.model";
import {ProjectsService} from "../../services/projects.service";
import {ImageModel} from "../../models/image.model";

@Component({
  templateUrl: 'projects.component.html'
})
export class ProjectsComponent {
  id: string | undefined;
  projects: ProjectModel[] = [];


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

  ngOnInit() {
    this.getProjects();
  }
}
