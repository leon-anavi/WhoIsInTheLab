package registration

import (
	"testing"
)

func TestThatPrintsHelloWorld(t *testing.T) {
	expected := "Hello World!"
	actual := Hello()
	if actual != expected {
		t.Errorf("expected %v, but was %v", expected, actual)
	}
}
