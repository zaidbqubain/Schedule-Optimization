library(testthat)

test_that(
  "all assignment should be placed in schedule if there are vacant slots",
  {
    expect_equal(length(event), length(assigments))
  }
)
  
