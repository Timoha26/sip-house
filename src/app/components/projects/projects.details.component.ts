import {Component} from "@angular/core";
import {ActivatedRoute} from "@angular/router";
import {ProjectModel} from "../../models/project.model";
import {ProjectsService} from "../../services/projects.service";

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
            break;
          }
      }
    });
  }

  ngOnInit() {
    if (this.id)
      this.getProject(this.id);
  }
}
