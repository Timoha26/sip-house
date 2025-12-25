import {Component} from "@angular/core";
import {ActivatedRoute} from "@angular/router";
import {ProjectModel} from "../../models/project.model";
import {ProjectsService} from "../../services/projects.service";
import {ImageModel} from "../../models/image.model";

@Component({
  templateUrl: 'projects.details.component.html'
})
export class ProjectsDetailsComponent {
  id: string | undefined;
  project: ProjectModel | undefined;

  constructor(private activatedRoute: ActivatedRoute, private projectsService: ProjectsService) {
    this.id = activatedRoute.snapshot.params['id'];
  }

  private getProject(id: string) {
    this.projectsService.get().subscribe({
      next: data => {
        for (let i = 0; i < data.length; i++)
          if (data[i].id == id) {
            this.project = data[i];
            this.project.images.sort((a,b) => a.sort - b.sort);
            break;
          }
      }
    });
  }

  getMainImage(images: ImageModel[]): string {
    for (let i = 0; i < images.length; i++)
      if (images[i].mainImage)
        return images[i].url;

    return '';
  }

  ngOnInit() {
    if (this.id)
      this.getProject(this.id);
  }
}
