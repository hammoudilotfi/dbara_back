import { ComponentFixture, TestBed } from '@angular/core/testing';

import { DbaretchefComponent } from './dbaretchef.component';

describe('DbaretchefComponent', () => {
  let component: DbaretchefComponent;
  let fixture: ComponentFixture<DbaretchefComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [DbaretchefComponent]
    });
    fixture = TestBed.createComponent(DbaretchefComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
